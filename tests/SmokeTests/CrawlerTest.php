<?php

namespace SmokeTests;

use App\Tests\SmokeTests\ProjectWebTestCase;

final class CrawlerTest extends ProjectWebTestCase
{
    public function testFrontpageLinks(): void
    {
        $client = parent::createClient();
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
