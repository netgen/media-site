<?php

declare(strict_types=1);

namespace App\GitHooks\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use SebastianFeldmann\Git\Repository;
use Symfony\Component\Process\Process;
use function array_diff;
use function array_merge;
use function implode;

final class CheckForBlockedWords extends Action
{
    protected const ERROR_MESSAGE = 'You have left blocked keyword(s) in your code!';

    protected function doExecute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        $files = $this->getChangedFiles($action, $repository);
        if (empty($files)) {
            return;
        }

        $filesArguments = implode(' ', $files);

        $blockedKeywords = $action->getOptions()->get('keyword_blocklist');
        $blockedarguments = implode('|', $blockedKeywords);

        $process = new Process(
            [
                'grep',
                '-iwnHE',
                $blockedarguments,
                $filesArguments,
            ]
        );

        $process->run();

        if ($process->getOutput()) {
            $io->writeError("<error>{$process->getOutput()}</error>");
            $this->throwError($action, $io);
        }
    }

    private function getChangedFiles(Config\Action $action, Repository $repository): array
    {
        $allowedFiles = $action->getOptions()->get('allowed_files');
        $extensions = $action->getOptions()->get('extensions', ['php', 'twig']);

        $changedFiles = [];
        foreach ($extensions as $extension) {
            $changedFiles = array_merge($changedFiles, $repository->getIndexOperator()->getStagedFilesOfType($extension));
        }

        return array_diff($changedFiles, $allowedFiles);
    }
}
