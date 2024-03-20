<?php

namespace SmokeTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CrawlerTest extends WebTestCase
{
    public function testFrontpageLinks(): void
    {
        $client = static::createClient(
            [],
            [
                'HTTP_HOST' => 'media-site.dev.php81.ez'
            ]
        );

        $client->setServerParameter('SCRIPT_FILENAME', 'index.php');
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $frontpageLinks = $crawler->filter('a')->links();

        $selectedLinkIndices = array_rand($frontpageLinks, 10);

        foreach($selectedLinkIndices as $index) {
            $link = $frontpageLinks[$index];
            fwrite(STDERR, print_r($link->getUri() . PHP_EOL, TRUE));
            $client->click($link);
            $this->assertResponseIsSuccessful();
        }
    }
}
