<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

use function end;
use function flush;
use function json_decode;
use function mb_strlen;
use function ob_flush;
use function sprintf;
use function usleep;

use const JSON_THROW_ON_ERROR;

class ProxyChat extends AbstractController
{
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

        $response->setCallback(static function () use ($client, $remoteResponse) {
            $data = '{"data":{"generateCopilotResponse":{"threadId":"ck-5eecb86e-2095-4769-88ea-73532be385a7","runId":null,"__typename":"CopilotResponse","messages":[]}},"hasNext":true}';
            echo "\r\n---\r\n";
            echo "Content-Type: application/json; charset=utf-8\r\n";
            echo 'Content-Length: ' . mb_strlen($data) . "\r\n";
            echo "\r\n";
            echo $data;
            echo "\r\n";

            ob_flush();
            flush();

            $data = '{"incremental":[{"items":[{"__typename":"TextMessageOutput","id":"ck-e3772f2f-22c4-4e31-a375-225313d0d1df","createdAt":"2025-01-13T10:31:09.776Z","role":"assistant","content":[]}],"path":["generateCopilotResponse","messages",0]}],"hasNext":true}';
            echo "\r\n---\r\n";
            echo "Content-Type: application/json; charset=utf-8\r\n";
            echo 'Content-Length: ' . mb_strlen($data) . "\r\n";
            echo "\r\n";
            echo $data;
            echo "\r\n";

            ob_flush();
            flush();

            $k = 1;
            foreach ($client->stream($remoteResponse) as $chunk) {
                $payload = $chunk->getContent();
                $data = '{"incremental":[{"items":["' . $payload . ' "],"path":["generateCopilotResponse","messages",0,"content",' . $k . ']}],"hasNext":true}';

                echo "\r\n---\r\n";
                echo "Content-Type: application/json; charset=utf-8\r\n";
                echo 'Content-Length: ' . mb_strlen($data) . "\r\n";
                echo "\r\n";
                echo $data;
                echo "\r\n";

                ob_flush();
                flush();

                ++$k;
                //                usleep(250000);
            }

            $data = '{"hasNext":false}';
            echo "---\r\n";
            echo "Content-Type: application/json; charset=utf-8\r\n";
            echo 'Content-Length: ' . mb_strlen($data) . "\r\n";
            echo "\r\n";
            echo $data;
            echo "\r\n";

            ob_flush();
            flush();

            echo "-----\r\n";

            ob_flush();
            flush();
        });

        return $response;
    }
}
