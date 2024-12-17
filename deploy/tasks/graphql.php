<?php

namespace Deployer;

set('graphql_generate_command', '{{bin/php}} {{bin/console}} ibexa:graphql:generate-schema');

task('app:graphql:deploy', [
    'app:graphql:generate',
]);

task('app:graphql:generate', function () {
    run("{{graphql_generate_command}}");
});
