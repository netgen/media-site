<?php

declare(strict_types=1);

namespace App\Tests\SmokeTests;

final class SearchTest extends ProjectWebTestCase
{
    public function testSearchWorks(): void
    {
        $client = parent::createClient();
        $crawler = $client->request('GET', '/content/search');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('a.site-logo');
        self::assertSelectorExists('input[name=searchText]');
    }
}
