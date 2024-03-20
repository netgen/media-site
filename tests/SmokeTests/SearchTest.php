<?php


namespace SmokeTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchTest extends WebTestCase
{
    public function testSearchWorks(): void
    {
        $client = static::createClient(
            [],
            [
                'HTTP_HOST' => 'media-site.dev.php81.ez'
            ]
        );

        $client->setServerParameter('SCRIPT_FILENAME', 'index.php');
        $crawler = $client->request('GET', '/content/search');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('a.site-logo');
        $this->assertSelectorExists('input[name=searchText]');
    }
}
