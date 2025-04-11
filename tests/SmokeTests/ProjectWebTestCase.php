<?php

declare(strict_types=1);

namespace App\Tests\SmokeTests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ProjectWebTestCase extends WebTestCase
{
    public static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        $client = parent::createClient($options, $server);

        $server['HTTP_HOST'] = static::getContainer()->getParameter('app.testing.site_domain');

        $client->setServerParameters($server);
        $client->setServerParameter('SCRIPT_FILENAME', 'index.php');

        return $client;
    }
}
