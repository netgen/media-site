<?php

namespace Deployer;

host('prod')
    ->setHostname('some.server.com')
    ->set('deploy_path', '/home/some/example/sites/example.com/htdocs')
    ->set('labels', ['stage' => 'prod'])
    ->setRemoteUser('exampleweb')
    ->set('http_user', 'exampleweb')
    ->set('cachetool', '/run/php/php8.1-fpm.sock')
    ->add('varnish_ban_hosts', ['www.example.com'])
    ->set('symfony_env', 'prod')
;

host('stage')
    ->setHostname('some.server.com')
    ->set('deploy_path', '/home/some/example/sites/example.com/htdocs')
    ->set('labels', ['stage' => 'stage'])
    ->setRemoteUser('exampleweb')
    ->set('http_user', 'exampleweb')
    // ->set('branch', 'test-branch') // by default, master is used, this is the way to set up different branch per host
    ->set('cachetool', '/run/php/php8.1-fpm.sock')
    ->add('varnish_ban_hosts', ['www.example.com'])
    ->set('symfony_env', 'stage')
;
