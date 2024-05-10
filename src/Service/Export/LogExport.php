<?php

declare(strict_types=1);

namespace App\Service\Export;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use function chr;
use function date;
use function fclose;
use function fopen;
use function fputcsv;
use function mb_convert_encoding;
use function rewind;
use function stream_get_contents;

final class LogExport
{
    public function __construct() {}

    /**
     * @param \App\Entity\Log[] $logs
     */
    public function exportLogs(array $logs): Response
    {
        $fileName = 'logs_export_' . date('Y-m_d_H_i') . '.csv';

        $content = $this->serializeToCsv($logs);

        $response = new Response($content);

        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);
        $response->setPrivate();
        $response->headers->addCacheControlDirective('no-cache');
        $response->headers->addCacheControlDirective('no-store');
        $response->headers->addCacheControlDirective('must-revalidate');

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName);

        return $response;
    }

    /**
     * @param \App\Entity\Log[] $logs
     */
    public function serializeToCsv(array $logs): string
    {
        $output = fopen('php://temp', 'w');

        if ($output === false) {
            throw new BadRequestHttpException('Failed to create temporary file for writing');
        }

        fputcsv(
            $output,
            [
                'Date',
                'Severity',
                'Module',
                'Email',
                'Message',
            ],
            "\t",
        );

        foreach ($logs as $log) {
            fputcsv($output, [
                $log->getDate()->format('d.m.Y H:i'),
                $log->getSeverity()->value,
                $log->getModule()->value,
                $log->getEmail(),
                $log->getMessage(),
            ], "\t");
        }

        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        if ($content === false) {
            throw new RuntimeException();
        }

        return chr(255) . chr(254) . mb_convert_encoding($content, 'UTF-16LE', 'UTF-8');
    }
}
