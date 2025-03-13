<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Vaites\ApacheTika\Client;

class TikaPdfExtractorExtension extends AbstractExtension
{
    public function __construct(
        private readonly string $tikaDomain,
        private readonly int $tikaPort,
    ) {}

    public function getFilters(): array
    {
        return [
            new TwigFilter('extract_pdf_text', [$this, 'extractPdfText']),
        ];
    }

    public function extractPdfText(string $filePath): string
    {
        try {
            $tika = Client::make($this->tikaDomain, $this->tikaPort);

            return $tika->getText($filePath) ?? '';
        } catch (\Exception $e) {
            return 'Error extracting text: ' . $e->getMessage();
        }
    }
}
