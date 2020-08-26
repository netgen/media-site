<?php

declare(strict_types=1);

namespace AppBundle\GitHooks\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use SebastianFeldmann\Git\Repository;
use function count;
use function in_array;
use function mb_strpos;

final class CheckAssetsAction extends Action
{
    protected $defaultErrorMessage = "I'm sorry, Dave. I'm afraid I can't do that. Please build production assets before committing changes";

    protected function doExecute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        $changedSassFiles = $repository->getIndexOperator()->getStagedFilesOfType('scss');
        $changedJSFiles = $repository->getIndexOperator()->getStagedFilesOfType('js');
        if (count($changedSassFiles) === 0 && count($changedJSFiles) === 0) {
            return;
        }

        if ($this->allowedOnly($changedSassFiles, $changedJSFiles, $action)) {
            return;
        }

        $changedCssFiles = $repository->getIndexOperator()->getStagedFilesOfType('css');

        $cssBuildFound = $this->checkCssBuildFiles($changedSassFiles, $changedCssFiles);
        $jsBuildFound = $this->checkJsBuildFiles($changedJSFiles);

        if (!$this->checkAssetsConfigFiles($repository)) {
            $this->throwError($action, $io);
        }

        if (!$cssBuildFound || !$jsBuildFound) {
            $this->throwError($action, $io);
        }
    }

    private function allowedOnly(array $changedSassFiles, array $changedJSFiles, Config\Action $action): bool
    {
        $allowList = $action->getOptions()->get('allowlist');

        foreach ($changedSassFiles as $changedSassFile) {
            if (!in_array($changedSassFile, $allowList, true)) {
                return false;
            }
        }

        foreach ($changedJSFiles as $changedJSFile) {
            if (!in_array($changedJSFile, $allowList, true)) {
                return false;
            }
        }

        return true;
    }

    private function checkAssetsConfigFiles(Repository $repository): bool
    {
        $changedJsonFiles = $repository->getIndexOperator()->getStagedFilesOfType('json');

        $entrypointFound = false;
        $manifestFound = false;
        foreach ($changedJsonFiles as $jsonFile) {
            if (mb_strpos($jsonFile, 'build/entrypoints.json') !== false) {
                $entrypointFound = true;

                continue;
            }

            if (mb_strpos($jsonFile, 'build/manifest.json') !== false) {
                $manifestFound = true;

                continue;
            }
        }

        return $entrypointFound && $manifestFound;
    }

    private function checkCssBuildFiles(array $changedSassFiles, array $changedCssFiles): bool
    {
        $cssBuildFound = count($changedSassFiles) ? false : true;

        foreach ($changedCssFiles as $cssFile) {
            if (mb_strpos($cssFile, 'build') === false) {
                continue;
            }

            $cssBuildFound = true;
        }

        return $cssBuildFound;
    }

    private function checkJsBuildFiles(array $changedJSFiles): bool
    {
        $jsBuildFound = count($changedJSFiles) ? false : true;
        foreach ($changedJSFiles as $changedJSFile) {
            if (mb_strpos($changedJSFile, 'build') !== false) {
                $jsBuildFound = true;

                break;
            }
        }

        return $jsBuildFound;
    }
}
