<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

use function flush;
use function ob_flush;
use function usleep;

class ProxyChat extends AbstractController
{
    /**
     * @Route("/ai-proxy")
     */
    public function __invoke(): StreamedResponse
    {
        $client = HttpClient::create();

        $response = new StreamedResponse();
        $response->headers->set('Content-Type', 'multipart/mixed; boundary="-"');
        $response->headers->set('X-Accel-Buffering', 'no');

        $remoteResponse = $client->request('GET', 'https://netgen-ai-site.dev10.netgen.biz/ai');

        $response->setCallback(static function () use ($client, $remoteResponse) {
            foreach ($client->stream($remoteResponse) as $chunk) {
                echo $chunk->getContent();

                ob_flush();
                flush();

                usleep(250000);
            }
        });

        return $response;
    }
}
