<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\LogRepository;
use App\Service\Export\LogExport;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

use function date;

#[AsCommand(
    name: 'ngsite:archive-logs',
    description: 'Executes monthly, deletes logs older than two months and saves old logs in csv.',
)]
final class ArchiveLogs extends Command
{
    public function __construct(
        private readonly LogRepository $logRepository,
        private readonly LogExport $logExport,
        private readonly Filesystem $filesystem,
        private readonly string $logsDir,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dateTo = new \DateTimeImmutable('first day of -2 month');
        $dateTo = $dateTo->setTime(0, 0, 0);
        $logsToArchive = $this->logRepository->search(null, null, null, null, null, $dateTo);

        $logs = [];
        foreach ($logsToArchive as $log) {
            $yearAndMonth = date('Y-m', $log->getDate()->getTimestamp());
            $logs[$yearAndMonth][] = $log;
        }

        if (!$this->filesystem->exists($this->logsDir)) {
            try {
                $this->filesystem->mkdir($this->logsDir);
            } catch (IOException $exception) {
                return Command::FAILURE;
            }
        }

        foreach ($logs as $yearAndMonth => $logsList) {
            $fileContent = $this->logExport->serializeToCsv($logsList);
            $fileName = 'logs_export_' . $yearAndMonth . '.csv';

            try {
                $this->filesystem->dumpFile($this->logsDir . '/' . $fileName, $fileContent);
            } catch (IOException $exception) {
                return Command::FAILURE;
            }

            foreach ($logsList as $log) {
                $this->logRepository->remove($log, true);
            }
        }

        return Command::SUCCESS;
    }
}
