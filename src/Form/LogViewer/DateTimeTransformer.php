<?php

declare(strict_types=1);

namespace App\Form\LogViewer;

use DateTimeImmutable;
use DateTimeZone;
use Symfony\Component\Form\DataTransformerInterface;
use Throwable;

/**
 * @implements \Symfony\Component\Form\DataTransformerInterface<
 *     \DateTimeImmutable,
 *     string
 * >
 */
final class DateTimeTransformer implements DataTransformerInterface
{
    public function transform($value): ?string
    {
        return $value?->format('Y-m-d');
    }

    public function reverseTransform($value): ?DateTimeImmutable
    {
        try {
            return $value !== '' && $value !== null ?
                new DateTimeImmutable($value, new DateTimeZone('UTC')) :
                null;
        } catch (Throwable) {
            return null;
        }
    }
}
