<?php

namespace Deployer;

use Deployer\Task\Context;
use Symfony\Component\Dotenv\Dotenv;

desc('Execute kaliop migrations');
task('database:kaliop:migrate', function () {
    run('{{bin/php}} {{bin/console}} kaliop:migration:migrate --env={{symfony_env}}');
});

desc('Download database dump');
task('database:dump', function () {
    if (!input()->hasArgument('stage')) {
        writeln('<error>Stage was not provided</error>');
        exit;
    }

    $stage = input()->getArgument('stage');
    $dotenvFilePath = __DIR__ . '/../files/.env.local.' . $stage;

    if (!file_exists($dotenvFilePath)) {
        writeln('<error>Env file was not found: ' . $dotenvFilePath . '</error>');
        return;
    }

    $dotenv = new Dotenv();
    $dotenv->load($dotenvFilePath);

    $databaseUrl = $_ENV['DATABASE_URL'] ?? null;

    if ($databaseUrl === null) {
        writeln("<error>DATABASE_URL environment variable not found</error>");
        exit;
    }

    $databaseParts = parse_url($databaseUrl);

    $databaseUser = $databaseParts['user'] ?? writeln("<error>Database user not found</error>") && exit;
    $databasePass = $databaseParts['pass'] ?? writeln("<error>Database password not found</error>") && exit;
    $databaseHost = $databaseParts['host'] ?? writeln("<error>Database host not found</error>") && exit;
    $databasePort = $databaseParts['port'] ?? 3306;
    $databaseName = ltrim($databaseParts['path'] ?? '', '/');

    $application = get('application');
    $timestamp = date('Y-m-d_H-i-s');
    $dumpFile = sprintf('%s_%s_%s.sql.gz', $application, $stage, $timestamp);
    $server = Context::get()->getHost();

    $command = sprintf(
        'ssh %s "mysqldump -u%s -p%s -h%s -P%s --no-tablespaces --lock-tables=false --opt --quick --single-transaction -v %s | gzip -c" > %s',
        $server,
        $databaseUser,
        $databasePass,
        $databaseHost,
        $databasePort,
        $databaseName,
        $dumpFile
    );

    runLocally($command, ['tty' => true]);

    writeln('<info>Database dumped to: ' . $dumpFile . '</info>');
});
