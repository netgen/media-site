<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class BookmarkExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('ngsite_bookmark', [BookmarkRuntime::class, 'getBookmark']),
        ];
    }
}
