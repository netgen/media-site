<?php

declare(strict_types=1);

namespace App\Tests\SmokeTests;

use Symfony\Component\Console\Output\ConsoleOutput;

use function array_rand;

final class CrawlerTest extends ProjectWebTestCase
{
    public function testFrontpageLinks(): void
    {
        $client = parent::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $frontpageLinks = $crawler->filter('a')->links();

        $selectedLinkIndices = array_rand($frontpageLinks, 10);

        $output = new ConsoleOutput();

        foreach ($selectedLinkIndices as $index) {
            $link = $frontpageLinks[$index];
            $output->writeln('Test page: ' . $link->getUri());
            $client->click($link);
            self::assertResponseIsSuccessful();
        }
    }
}
