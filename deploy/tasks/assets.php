<?php

namespace Deployer;

// parameters
set('asset_resource_paths', [
    'assets/js',
    'assets/sass'
]);
set('asset_build_paths', [
    'assets',
]);
set('asset_exclude_paths', [
    'assets/app/build_dev'
]);
set('asset_install_command', 'yarn install');
set('asset_build_command', 'yarn build:prod');
set('asset_ibexa_build_command', function() {
   $composer = get('bin/composer');

   return $composer . ' ibexa-assets';
});

task('app:assets:deploy', [
    'app:assets:build',
    'app:assets:ibexa:build',
    'app:assets:upload'
]);

task('app:assets:build', function() {
    writeln('<comment>Checking for changes in asset files. If this fails, commit or stash your changes before deploying.</comment>');
    $assetResourcePaths = get('asset_resource_paths');
    foreach ($assetResourcePaths as $resourcePath) {
        runLocally("git diff --exit-code {$resourcePath}");
    }

    $installCmd = get('asset_install_command');
    runLocally($installCmd);

    $buildCmd = get('asset_build_command');
    runLocally($buildCmd);
})->once();

task('app:assets:ibexa:build', function() {
    runLocally("{{asset_ibexa_build_command}}");
})->once();

task('app:assets:upload', function() {
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
