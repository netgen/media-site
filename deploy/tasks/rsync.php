<?php

namespace Deployer;

set('rsync_src_storage', '{{deploy_path}}/shared/ezpublish_legacy/var/site/storage');
set('rsync_dest_storage', __DIR__ . '/../../ezpublish_legacy/var/site/storage');

desc('Rsync legacy storage remote->local');
task('rsync:storage', function() {
    $config = get('rsync');

    $src = get('rsync_src_storage');
    while (is_callable($src)) {
        $src = $src();
    }

    if (!trim($src)) {
        // if $src is not set here rsync is going to do a directory listing
        // exiting with code 0, since only doing a directory listing clearly
        // is not what we want to achieve we need to throw an exception
        throw new \RuntimeException('You need to specify a source path.');
    }

    $dst = get('rsync_dest_storage');
    while (is_callable($dst)) {
        $dst = $dst();
    }

    if (!trim($dst)) {
        // if $dst is not set here we are going to sync to root
        // and even worse - depending on rsync flags and permission -
        // might end up deleting everything we have write permission to
        throw new \RuntimeException('You need to specify a destination path.');
    }

    $server = \Deployer\Task\Context::get()->getHost();
    if ($server instanceof \Deployer\Host\Localhost) {
        runLocally("rsync -{$config['flags']} {{rsync_options}}{{rsync_includes}}{{rsync_excludes}}{{rsync_filter}} '$src/' '$dst/'", $config);
        return;
    }

    $host = $server->getRealHostname();
    $port = $server->getPort() ? ' -p' . $server->getPort() : '';
    $sshArguments = $server->getSshArguments();
    $user = !$server->getUser() ? '' : $server->getUser() . '@';

    runLocally("rsync -{$config['flags']} -e 'ssh$port $sshArguments' {{rsync_options}}{{rsync_includes}}{{rsync_excludes}}{{rsync_filter}} '$user$host:$src/' '$dst/'", $config);
});
