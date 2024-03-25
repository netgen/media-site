<?php

namespace Deployer;

task('app:test:phpunit', function () {
    $domains = get('testing_domains');

    foreach ($domains as $domain) {
        runLocally("TEST_DOMAIN={$domain} {{local_php_path}} vendor/bin/phpunit");
    }
});
