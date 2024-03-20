<?php


namespace SmokeTests;

use App\Tests\SmokeTests\ProjectWebTestCase;

final class SearchTest extends ProjectWebTestCase
{
    public function testSearchWorks(): void
    {
        $client = parent::createClient();
        $crawler = $client->request('GET', '/content/search');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('a.site-logo');
        $this->assertSelectorExists('input[name=searchText]');
    }
}
