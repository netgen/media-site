<?php

namespace Deployer;

desc('Import translations to the database');
task('translations:import', function () {
    run('{{bin/php}} {{bin/console}} lexik:translations:import --env={{symfony_env}} AppBundle');
});
