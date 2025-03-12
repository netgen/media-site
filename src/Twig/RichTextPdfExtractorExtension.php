<?php

declare(strict_types=1);

namespace App\Twig;

namespace App\Twig;

use DOMDocument;
use DOMXPath;
use Ibexa\Contracts\Core\Repository\Repository;
use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\IbexaSiteApi\API\Values\Location;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RichTextPdfExtractorExtension extends AbstractExtension
{
    public function __construct(
        private readonly Repository $repository,
        private readonly LoadService $loadService,
    ) {}

    public function getFilters()
    {
        return [
            new TwigFilter('extract_embedded_pdfs', [$this, 'extractEmbeddedPdfs']),
        ];
    }

    /** @return Content[] */
    public function extractEmbeddedPdfs(DOMDocument $doc): array
    {
        $document = clone $doc;

        $pdfs = [];
        $xpath = new DOMXPath($document);

        $xpath->registerNamespace('docbook', 'http://docbook.org/ns/docbook');
        $xpath->registerNamespace('xlink', 'http://www.w3.org/1999/xlink');

        $linkAttributeEzLocation = "starts-with( @xlink:href, 'ezlocation://' )";
        $linkAttributeEzContent = "starts-with( @xlink:href, 'ezcontent://' )";

        $linkAttributeCondition = $linkAttributeEzLocation . ' or ' . $linkAttributeEzContent;

        $xpathExpressionLink = \sprintf('//docbook:link[%s]|//docbook:ezlink', $linkAttributeCondition);
        $xpathExpressionEzembedinline = \sprintf('//docbook:ezembedinline[%s]', $linkAttributeCondition);

        $fullExpression = $xpathExpressionLink . '|' . $xpathExpressionEzembedinline;

        $linksAndEmbeds = $xpath->query($fullExpression);

        if ($linksAndEmbeds === false) {
            return [];
        }

        foreach ($linksAndEmbeds as $element) {
            if (!$element instanceof \DOMElement) {
                continue;
            }

            $href = $element->getAttribute('xlink:href');
            \preg_match('~^(.+://)?([^#]*)?(#.*|\s*)?$~', $href, $matches);

            $scheme = $matches[1] ?? '';
            $id = isset($matches[2]) ? (int) $matches[2] : null;

            if ($scheme === 'ezcontent://') {
                $content = $this->loadContent((int) $id);
                $pdfs[] = $content;
            } elseif ($scheme === 'ezlocation://') {
                $location = $this->loadLocation((int) $id);
                $pdfs[] = $location->content;
            }
        }

        return $pdfs;
    }

    private function loadContent(int $id): Content
    {
        return $this->repository->sudo(
            fn (): Content => $this->loadService->loadContent($id),
        );
    }

    private function loadLocation(int $id): Location
    {
        return $this->repository->sudo(
            fn (): Location => $this->loadService->loadLocation($id),
        );
    }
}
