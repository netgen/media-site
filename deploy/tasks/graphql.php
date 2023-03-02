<?php

namespace Deployer;

set('graphql_generate_command', '{{local_php_path}} bin/console ezplatform:graphql:generate-schema');

task('app:graphql:deploy', [
    'app:graphql:generate',
    'app:graphql:upload'
]);

task('app:graphql:generate', function () {
    run("{{graphql_generate_command}}");
})->local();


task('app:graphql:upload', function () {
    upload("app/config/graphql", "{{release_path}}/app/config");
});
