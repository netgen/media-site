<?php

namespace Deployer;

// parameters
set('asset_resource_paths', [
    'src/AppBundle/Resources/es6',
    'src/AppBundle/Resources/sass'
]);
set('asset_build_paths', [
    'assets',
]);
set('asset_exclude_paths', [
   'assets/app/build_dev'
]);
set('asset_install_command', 'yarn install');
set('asset_build_command', 'yarn build:prod');
set('asset_ezplatform_build_command', 'composer ezplatform-assets');

task('assets:deploy', [
    'assets:build',
    'assets:ezplatform:build',
    'assets:upload'
]);

task('assets:build', function() {
    writeln('<comment>Checking for changes in asset files. If this fails, commit or stash your changes before deploying.</comment>');
    $assetResourcePaths = get('asset_resource_paths');
    foreach ($assetResourcePaths as $resourcePath) {
        run("git diff --exit-code {$resourcePath}");
    }

    $installCmd = get('asset_install_command');
    run($installCmd);

    $buildCmd = get('asset_build_command');
    run($buildCmd);
})->local();

task('assets:ezplatform:build', function() {
    run("{{asset_ezplatform_build_command}}");
})->local();

task('assets:upload', function() {
    $assetPaths = get('asset_build_paths');
    $excludedPaths = get('asset_exclude_paths', []);

    $config = [];
    foreach ($excludedPaths as $excludedPath) {
        $config['options'][] = "--exclude {$excludedPath}";
    }

    foreach ($assetPaths as $path) {
        upload("public/{$path}", '{{release_path}}/public', $config);
    }
});
