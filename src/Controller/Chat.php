<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

use function flush;
use function mb_strlen;
use function ob_flush;
use function usleep;

class Chat extends AbstractController
{
    /**
     * @Route("/ai")
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

            for ($i = 0; $i < 10; ++$i) {
                usleep(250000);

                $data = '{"incremental":[{"items":[{"__typename":"TextMessageOutput","id":"ck-55f792ec-e160-42ed-8411-30ce3507ff7d","createdAt":"2025-01-16T06:27:15.711Z","role":"assistant","content":[]}],"path":["generateCopilotResponse","messages",0]}],"hasNext":true}';
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
