<?php

namespace Deployer;

// parameters
set('graphql_config_path', 'config/graphql');
set('graphql_config_destination', 'config');
set('local_php_path', '/usr/bin/env php8.1');
set('graphql_generate_command', '{{local_php_path}} bin/console ibexa:graphql:generate-schema');

task('app:graphql:deploy', [
    'app:graphql:generate',
    'app:graphql:upload'
]);

task('app:graphql:generate', function () {
    run("{{graphql_generate_command}}");
})->local();


task('app:graphql:upload', function () {
    upload("{{graphql_config_path}}", "{{release_path}}/{{graphql_config_destination}}");
});
