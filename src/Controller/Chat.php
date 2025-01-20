<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

use function explode;
use function flush;
use function mb_strlen;
use function ob_flush;
use function usleep;

class Chat extends AbstractController
{
    /**
     * @Route("/ai-proxy")
     */
    public function __invoke(): StreamedResponse
    {
        $response = new StreamedResponse();

        $response->headers->set('Content-Type', 'multipart/mixed; boundary="-"');
        $response->headers->set('X-Accel-Buffering', 'no');

        $response->setCallback(static function () {
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

            $responseString = 'This is a fake response from chat backend, test test.';
            $words = explode(' ', $responseString); // Split the string into words

            for ($i = 0; $i < 10; ++$i) {
                usleep(100000);

                $data = '{"incremental":[{"items":["' . $words[$i] . ' "],"path":["generateCopilotResponse","messages",0,"content",' . $i . ']}],"hasNext":true}';
                echo "---\r\n";
                echo "Content-Type: application/json; charset=utf-8\r\n";
                echo 'Content-Length: ' . mb_strlen($data) . "\r\n";
                echo "\r\n";
                echo $data;
                echo "\r\n";

                ob_flush();
                flush();
            }

            usleep(250000);

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
