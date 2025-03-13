<?php

declare(strict_types=1);

namespace App\Twig;

use App\Services\ApacheTikaService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TikaPdfExtractorExtension extends AbstractExtension
{
    public function __construct(
        private readonly ApacheTikaService $apacheTikaService,
    ) {}

    public function getFilters(): array
    {
        return [
            new TwigFilter('extract_pdf_text', [$this, 'extractPdfText']),
        ];
    }

    public function extractPdfText(string $filePath): string
    {
        // $this->apacheTikaService->extractTextAsProcess($filePath);
        return $this->apacheTikaService->extractTextAsServer($filePath);
    }
}
