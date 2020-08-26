<?php

declare(strict_types=1);

namespace AppBundle\GitHooks\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Message\Action\Regex as BaseRegex;
use SebastianFeldmann\Git\Repository;

final class Regex extends Action
{
    protected function doExecute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        $innerRegex = new BaseRegex();
        $innerRegex->execute($config, $io, $repository, $action);
    }
}
