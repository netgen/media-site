<?php

declare(strict_types=1);

namespace App\Tests\SmokeTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ProjectWebTestCase extends WebTestCase
{
    public static function createClient(array $options = [], array $server = [])
    {
        $server['HTTP_HOST'] = 'media-site.dev.php81.ez'; // @todo
        $client = parent::createClient($options, $server);

        $client->setServerParameter('SCRIPT_FILENAME', 'index.php');

        return $client;
    }
}
