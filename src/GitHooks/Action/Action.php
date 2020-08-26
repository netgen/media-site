<?php

declare(strict_types=1);

namespace App\GitHooks\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Config\Action as ActionConfig;
use CaptainHook\App\Config\Options;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Hook\Action as ActionInterface;
use SebastianFeldmann\Git\Repository;

abstract class Action implements ActionInterface
{
    protected const ERROR_MESSAGE = "I'm sorry, Dave. I'm afraid I can't do that. Please check your commit for errors";

    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        if (!$this->isEnabled($action)) {
            return;
        }

        $this->doExecute($config, $io, $repository, $action);
    }

    abstract protected function doExecute(Config $config, IO $io, Repository $repository, Config\Action $action): void;

    protected function throwError(ActionConfig $config, IO $io): void
    {
        $errorMessage = $this->getErrorMessage($config->getOptions());

        $io->writeError("<error>{$errorMessage}</error>");

        throw new ActionFailed($errorMessage);
    }

    private function getErrorMessage(Options $options): string
    {
        return $options->get('error') ?? static::ERROR_MESSAGE;
    }

    private function isEnabled(ActionConfig $action): bool
    {
        return $action->getOptions()->get('enabled', true);
    }
}
