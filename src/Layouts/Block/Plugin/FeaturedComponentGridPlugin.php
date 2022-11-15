<?php

declare(strict_types=1);

namespace App\Layouts\Block\Plugin;

use Netgen\Layouts\Block\BlockDefinition\Handler\Plugin;
use Netgen\Layouts\Ibexa\Block\BlockDefinition\Handler\ComponentHandler;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Parameters\ParameterType;

final class FeaturedComponentGridPlugin extends Plugin
{
    /**
     * The list of columns available. Key should be number of columns, while values
     * should be human readable names of the columns.
     *
     * @var string[]
     */
    private array $columns;

    /**
     * @param string[] $columns
     */
    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public static function getExtendedHandlers(): iterable
    {
        yield ComponentHandler::class;
    }

    public function buildParameters(ParameterBuilderInterface $builder): void
    {
        $builder->add(
            'number_of_columns',
            ParameterType\ChoiceType::class,
            [
                'required' => true,
                'default_value' => 3,
                'options' => array_flip($this->columns),
                'label' => 'block.plugin.featured_grid.number_of_columns',
                'groups' => [self::GROUP_DESIGN],
            ],
        );
    }
}
