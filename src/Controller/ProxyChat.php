<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function end;
use function flush;
use function json_decode;
use function mb_strlen;
use function ob_flush;
use function sprintf;

use const JSON_THROW_ON_ERROR;

class ProxyChat extends AbstractController
{
    private const NEW_LINE = "\r\n";

    /**
     * @Route("/ai")
     */
    public function __invoke(Request $request): StreamedResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $messages = $data['variables']['data']['messages'];
        $lastMessage = end($messages);
        $payload = $lastMessage['textMessage']['content'];

        $client = HttpClient::create();

        $response = new StreamedResponse();
        $response->headers->set('Content-Type', 'multipart/mixed; boundary="-"');
        $response->headers->set('X-Accel-Buffering', 'no');

        $remoteResponse = $client->request(
            'POST',
            // send_message_to_rag
            'http://192.168.10.219:8000/api/rag/send_message_to_llm',
            [
                // + session_id, filter_field
                'body' => sprintf('{"query": "%s"}', $payload),
                'headers' => [
                    'accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ],
        );

        $response->setCallback(function () use ($remoteResponse) {
            $this->dumpAndFlushProlog();
            $this->streamRemoteResponse($remoteResponse);
            $this->dumpAndFlushClose();
        });

        return $response;
    }

    private function streamRemoteResponse(ResponseInterface $response): void
    {
        $client = HttpClient::create();

        $step = 0;

        foreach ($client->stream($response) as $chunk) {
            $data = sprintf(
                '{"incremental":[{"items":["%s"],"path":["generateCopilotResponse","messages",0,"content",%d]}],"hasNext":true}',
                $chunk->getContent(),
                $step,
            );

            $this->dumpAndFlushChunk($data);

            ++$step;
        }
    }

    private function dumpAndFlushProlog(): void
    {
        $data = '{"data":{"generateCopilotResponse":{"threadId":"ck-5eecb86e-2095-4769-88ea-73532be385a7","runId":null,"__typename":"CopilotResponse","messages":[]}},"hasNext":true}';
        $this->dumpChunk($data);

        $data = '{"incremental":[{"items":[{"__typename":"TextMessageOutput","id":"ck-e3772f2f-22c4-4e31-a375-225313d0d1df","createdAt":"2025-01-13T10:31:09.776Z","role":"assistant","content":[]}],"path":["generateCopilotResponse","messages",0]}],"hasNext":true}';
        $this->dumpChunk($data);

        $this->flush();
    }

    private function dumpChunk(string $data): void
    {
        echo '---';
        echo self::NEW_LINE;
        echo 'Content-Type: application/json; charset=utf-8';
        echo self::NEW_LINE;
        echo sprintf('Content-Length: %d', mb_strlen($data));
        echo self::NEW_LINE;
        echo self::NEW_LINE;
        echo $data;
        echo self::NEW_LINE;
    }

    private function dumpAndFlushChunk(string $data): void
    {
        $this->dumpChunk($data);
        $this->flush();
    }

    private function dumpAndFlushClose(): void
    {
        $this->dumpChunk('{"hasNext":false}');

        echo '-----';
        echo self::NEW_LINE;

        $this->flush();
    }

    private function flush(): void
    {
        ob_flush();
        flush();
    }
}
