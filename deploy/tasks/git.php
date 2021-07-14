<?php

namespace Deployer;

desc('Create a tag on git repository');
task('git:tag:add', function () {
    $git = get('bin/git');
    $host = get('hostname');
    $branch = get('branch', 'unknown');
    $date = new \DateTimeImmutable();

    $tag = $date->format('Y.m.d.H.i');
    $message = 'Deployed branch ' . $branch . ' to ' . $host;

    cd('{{release_path}}');

    $commitHash = run('git rev-parse HEAD');

    runLocally("$git tag -a $tag -m '" . $message . "' " . trim($commitHash));
    runLocally("$git push origin $tag");
})->onStage('prod');
