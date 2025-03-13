<?php

declare(strict_types=1);

namespace App\Services;

use Ibexa\Contracts\Core\Persistence\Content\Field;
use RuntimeException;
use Symfony\Component\Process\Process;
use Vaites\ApacheTika\Client;

use function sprintf;

/**
 * Extract text from Ibexa file based field types.
 */
final class ApacheTikaService
{
    public function __construct(
        private readonly string $projectDir,
        private readonly string $apacheTikaCliPath,
        private readonly string $javaDir,
        private readonly string $tikaDomain,
        private readonly string $tikaPort,
    ) {}

    public function extractTextAsProcess(string $filePath): string
    {
        $process = new Process(
            [
                $this->javaDir,
                '-jar',
                $this->apacheTikaCliPath,
                '--text',
                sprintf('%s', $filePath),
            ],
            $this->projectDir,
        );

        $process->run();
        $exitCode = $process->getExitCode();

        if ($exitCode !== 0) {
            throw new RuntimeException(
                sprintf(
                    'Could not extract text from file "%s": %s',
                    $filePath,
                    $process->getExitCodeText(),
                ),
            );
        }

        return $process->getOutput();
    }

    public function extractTextAsServer(string $filePath): string
    {
        try {
            $tika = Client::make($this->tikaDomain, $this->tikaPort);

            return $tika->getText($filePath) ?? '';
        } catch (\Exception $e) {
            return 'Error extracting text: ' . $e->getMessage();
        }
    }
}
