<?php

declare(strict_types=1);

namespace App\Attribute;

use Attribute;

/**
 * Service tag to autoconfigure KNP Menu builders.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AsMenuBuilder
{
    public function __construct(
        public readonly string $method,
        public readonly string $alias,
    ) {}
}
