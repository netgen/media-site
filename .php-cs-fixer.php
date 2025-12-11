<?php

// To support running PHP CS Fixer via PHAR file (e.g. in GitHub Actions)
require_once __DIR__ . '/vendor/netgen/coding-standard/lib/PhpCsFixer/Config.php';

return new Netgen\CodingStandard\PhpCsFixer\Config()
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(['src', 'tests'])
    )
;
