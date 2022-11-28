<?php

declare(strict_types=1);

namespace App\Templating\Twig\Extension;

use Netgen\Bundle\EnhancedSelectionBundle\Core\FieldType\EnhancedSelection\Value;
use Netgen\IbexaSiteApi\API\Values\Content;
use Twig\Extension\RuntimeExtensionInterface;

use function array_values;
use function preg_match;
use function reset;
use function usort;

final class ComponentRuntime implements RuntimeExtensionInterface
{
    private const COMPONENT_ITEMS_GROUP = 'group';
    private const CONTENT_ITEMS_GROUP_POSITION = 'position';
    private const COMPONENT_ITEMS_FIELD = 'field';

    public function mapComponentItems(Content $content, string $group): array
    {
        $regex = '/^' . $group . '_(?<' . self::COMPONENT_ITEMS_GROUP . '>\d+)_(?<' . self::COMPONENT_ITEMS_FIELD . '>.*)$/';
        $items = [];

        foreach ($content->fields as $field) {
            if (preg_match($regex, $field->fieldDefIdentifier, $matches)) {
                $items[$matches[self::COMPONENT_ITEMS_GROUP]][$matches[self::COMPONENT_ITEMS_FIELD]] = $field;
            }
        }

        $items = array_values($items);

        usort(
            $items,
            static function ($group1, $group2) {
                /** @var Value $value1 */
                $value1 = $group1[self::CONTENT_ITEMS_GROUP_POSITION]->value;

                /** @var Value $value2 */
                $value2 = $group2[self::CONTENT_ITEMS_GROUP_POSITION]->value;

                return (int) reset($value1->identifiers) <=> (int) reset($value2->identifiers);
            },
        );

        return $items;
    }
}
