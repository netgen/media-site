<?php

namespace Deployer;

task('app:test:phpunit', function () {
    runLocally('{{local_php_path}} vendor/bin/phpunit');
});
