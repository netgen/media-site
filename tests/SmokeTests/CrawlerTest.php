<?php

namespace SmokeTests;

use App\Tests\SmokeTests\ProjectWebTestCase;
use Symfony\Component\Console\Output\ConsoleOutput;

final class CrawlerTest extends ProjectWebTestCase
{
    public function testFrontpageLinks(): void
    {
        $client = parent::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $frontpageLinks = $crawler->filter('a')->links();

        $selectedLinkIndices = array_rand($frontpageLinks, 10);

        $output = new ConsoleOutput();

        foreach($selectedLinkIndices as $index) {
            $link = $frontpageLinks[$index];
            $output->writeln('Test page: ' . $link->getUri());
            $client->click($link);
            $this->assertResponseIsSuccessful();
        }
    }
}
