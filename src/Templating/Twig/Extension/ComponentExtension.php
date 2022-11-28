<?php

declare(strict_types=1);

namespace App\Templating\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ComponentExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'ng_component_items',
                [ComponentRuntime::class, 'mapComponentItems'],
            ),
        ];
    }
}
