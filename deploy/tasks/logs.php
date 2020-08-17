<?php

namespace Deployer;

desc('Display legacy log from the server');
task('logs:read:legacy', function () {
    $file = get('deploy_path') . '/current/' . get('legacy_error_log');

    $result = run('cat '.$file);

    write($result);
});

desc('Display symfony log from the server');
task('logs:read:symfony', function () {
    $file = get('deploy_path') . '/shared/' . get('symfony_error_log');

    $result = run('cat '.$file);

    write($result);
});
