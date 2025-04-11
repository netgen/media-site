<?php

declare(strict_types=1);

namespace App\Tests\SmokeTests;

final class FrontpageTest extends ProjectWebTestCase
{
    public function testFrontpageWorks(): void
    {
        $client = parent::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('a.site-logo');
    }
}
