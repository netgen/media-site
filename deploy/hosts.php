<?php

namespace Deployer;

host('prod')
    ->setHostname('some.server.com')
    ->set('deploy_path', '/home/some/example/sites/example.com/htdocs')
    ->setLabels(['stage' => 'prod'])
    ->setRemoteUser('exampleweb')
    ->set('http_user', 'exampleweb')
    ->set('cachetool', '/var/run/php/exampleweb.sock')
    ->add('varnish_ban_hosts', ['www.example.com'])
;

host('stage')
    ->setHostname('192.0.2.10')
    ->set('deploy_path', '/var/www/my/project')
    ->setLabels(['stage' => 'stage'])
    ->setRemoteUser('deployer')
    ->set('http_user', 'www-data')
    // ->set('branch', 'test-branch') // by default, master is used, this is the way to set up different branch per host
    ->set('cachetool', '/var/run/php/exampleweb.sock')
    ->add('varnish_ban_hosts', ['stage.example.com'])
;
