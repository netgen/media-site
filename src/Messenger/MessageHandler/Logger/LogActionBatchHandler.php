<?php

declare(strict_types=1);

namespace App\Messenger\MessageHandler\Logger;

use App\Entity\Log;
use App\Messenger\Message\Logger\LogAction;
use App\Repository\LogRepository;
use Symfony\Component\Messenger\Handler\Acknowledger;
use Symfony\Component\Messenger\Handler\BatchHandlerInterface;
use Symfony\Component\Messenger\Handler\BatchHandlerTrait;
use Throwable;

final class LogActionBatchHandler implements BatchHandlerInterface
{
    use BatchHandlerTrait;

    public function __construct(private LogRepository $logRepository) {}

    public function __invoke(LogAction $logAction, ?Acknowledger $ack = null): mixed
    {
        return $this->handle($logAction, $ack);
    }

    /**
     * @param array{0: object, 1: \Symfony\Component\Messenger\Handler\Acknowledger} $jobs
     */
    private function process(array $jobs): void
    {
        try {
            $this->persistLogs($jobs);
            $this->logRepository->flush();
        } catch (Throwable $e) {
            $this->markJobsAsNack($jobs, $e);

            return;
        }

        $this->markJobsAsAck($jobs);
    }

    /**
     * @param array{0: object, 1: \Symfony\Component\Messenger\Handler\Acknowledger} $jobs
     */
    private function persistLogs(array $jobs): void
    {
        /**
         * @var \App\Messenger\Message\Logger\LogAction $logAction
         * @var \Symfony\Component\Messenger\Handler\Acknowledger $ack
         */
        foreach ($jobs as [$logAction, $ack]) {
            $log = Log::fromLogAction($logAction);
            $this->logRepository->save($log);
        }
    }

    /**
     * @param array{0: object, 1: \Symfony\Component\Messenger\Handler\Acknowledger} $jobs
     */
    private function markJobsAsNack(array $jobs, Throwable $e): void
    {
        foreach ($jobs as [$logAction, $ack]) {
            $ack->nack($e);
        }
    }

    /**
     * @param array{0: object, 1: \Symfony\Component\Messenger\Handler\Acknowledger} $jobs
     */
    private function markJobsAsAck(array $jobs): void
    {
        /**
         * @var \App\Messenger\Message\Logger\LogAction $logAction
         * @var \Symfony\Component\Messenger\Handler\Acknowledger $ack
         */
        foreach ($jobs as [$logAction, $ack]) {
            $ack->ack($logAction);
        }
    }
}
