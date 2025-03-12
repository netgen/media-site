<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Vaites\ApacheTika\Client;

class TikaPdfExtractorExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('extract_pdf_text', [$this, 'extractPdfText']),
        ];
    }

    public function extractPdfText(string $filePath): string
    {
        try {
            $tika = Client::make('127.0.0.1', 9998);

            return $tika->getText($filePath) ?? '';
        } catch (\Exception $e) {
            return 'Error extracting text: ' . $e->getMessage();
        }
    }
}
