<?php

namespace Deployer;

desc('Display symfony log from the server');
task('logs:read:symfony', function () {
    $file = get('deploy_path') . '/shared/' . get('symfony_error_log');

    $result = run('cat '.$file);

    write($result);
});
