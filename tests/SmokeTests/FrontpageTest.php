<?php

namespace SmokeTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontpageTest extends WebTestCase
{
    public function testFrontpageWorks(): void
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
        $this->assertSelectorExists('a.site-logo');
    }
}
