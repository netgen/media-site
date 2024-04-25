<?php

declare(strict_types=1);

namespace App\Form\Filter;

use BackedEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function get_debug_type;
use function is_a;
use function is_array;
use function is_callable;
use function is_string;
use function sprintf;

final class SelectionsType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('translation_domain', 'backoffice');

        $resolver->setRequired('translation_prefix');
        $resolver->setAllowedTypes('translation_prefix', 'string');

        $resolver->setRequired('selections');
        $resolver->setAllowedTypes('selections', 'array');
        $resolver->setDefault('selections', []);

        $resolver->setAllowedValues(
            'selections',
            static function (array $selections): bool {
                foreach ($selections as $identifier => $selection) {
                    if (
                        is_callable($selection['options'])
                        || is_array($selection['options'])
                        || (is_string($selection['options']) && is_a($selection['options'], BackedEnum::class, true))
                    ) {
                        continue;
                    }

                    throw new InvalidOptionsException(
                        sprintf(
                            'Choices for "%s" identifier must be a callable or an array or a class string for a backed enum, %s given.',
                            $identifier,
                            get_debug_type($selection['options']),
                        ),
                    );
                }

                return true;
            },
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($options['selections'] as $identifier => $choices) {
            if (is_callable($choices['options']) || (is_array($choices['options']) && is_callable($choices['options'][0] ?? null))) {
                $callable = $choices['options'];
                $params = [];

                if (!is_callable($callable)) {
                    $callable = $choices['options'][0] ?? static fn (): array => [];
                    $params = $choices['options'][1] ?? [];
                }

                $builder->add(
                    $identifier,
                    Type\ChoiceType::class,
                    [
                        'required' => $choices['required'] ?? false,
                        'choices' => $callable(...$params),
                        'label' => sprintf('%s.filter.%s.label', $options['translation_prefix'], $identifier),
                        'multiple' => $choices['multiple'] ?? false,
                        'data' => $choices['default_value'] ?? null,
                    ],
                );
            } elseif (is_array($choices['options'])) {
                $builder->add(
                    $identifier,
                    Type\ChoiceType::class,
                    [
                        'required' => $choices['required'] ?? false,
                        'choices' => $choices['options'],
                        'choice_label' => static fn (string $choice): string => sprintf('%s.%s.%s', $options['translation_prefix'], $identifier, $choice),
                        'label' => sprintf('%s.filter.%s.label', $options['translation_prefix'], $identifier),
                        'multiple' => $choices['multiple'] ?? false,
                        'data' => $choices['default_value'] ?? null,
                    ],
                );
            } elseif (is_string($choices['options'])) {
                $builder->add(
                    $identifier,
                    Type\EnumType::class,
                    [
                        'required' => $choices['required'] ?? false,
                        'class' => $choices['options'],
                        'choice_label' => static fn (BackedEnum $choice): string => sprintf('%s.%s.%s', $options['translation_prefix'], $identifier, $choice->value),
                        'label' => sprintf('%s.filter.%s.label', $options['translation_prefix'], $identifier),
                        'multiple' => $choices['multiple'] ?? false,
                        'data' => $choices['default_value'] ?? null,
                    ],
                );
            }
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['translation_prefix'] = $options['translation_prefix'];
    }
}
