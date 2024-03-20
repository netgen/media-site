<?php

namespace SmokeTests;

use App\Tests\SmokeTests\ProjectWebTestCase;

final class FrontpageTest extends ProjectWebTestCase
{
    public function testFrontpageWorks(): void
    {
        $client = parent::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('a.site-logo');
    }
}
