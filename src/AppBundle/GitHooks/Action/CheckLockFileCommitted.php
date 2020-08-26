<?php

declare(strict_types=1);

namespace AppBundle\GitHooks\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use SebastianFeldmann\Git\Repository;
use function in_array;

final class CheckLockFileCommitted extends Action
{
    protected const ERROR_MESSAGE = 'There is no corresponding lock file in your commit!';

    protected function doExecute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        $changedJsonFiles = $repository->getIndexOperator()->getStagedFilesOfType('json');

        if (!in_array('composer.json', $changedJsonFiles, true) && !in_array('package.json', $changedJsonFiles, true)) {
            return;
        }

        $changedLockFiles = $repository->getIndexOperator()->getStagedFilesOfType('lock');

        if (in_array('composer.json', $changedJsonFiles, true) && !in_array('composer.lock', $changedLockFiles, true)) {
            $io->writeError('composer.json has changed, but no composer.lock detected in the commit.');
            $this->throwError($action, $io);
        }

        if (in_array('package.json', $changedJsonFiles, true) && !in_array('yarn.lock', $changedLockFiles, true)) {
            $io->writeError('package.json has changed, but no yarn.lock detected in the commit.');
            $this->throwError($action, $io);
        }
    }
}
