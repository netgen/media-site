<?php

declare(strict_types=1);

namespace App\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function sprintf;

final class DateRangeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('translation_prefix');
        $resolver->setAllowedTypes('translation_prefix', 'string');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'date',
            Type\TextType::class,
            [
                'required' => false,
                'label' => sprintf('%s.filter.date.label', $options['translation_prefix']),
            ],
        );

        $builder->add(
            'dateFrom',
            Type\DateTimeType::class,
            [
                'html5' => false,
                'input' => 'string',
                'widget' => 'single_text',
                'required' => false,
            ],
        );

        $builder->get('dateFrom')->addModelTransformer(new DateTimeTransformer());

        $builder->add(
            'dateTo',
            Type\DateTimeType::class,
            [
                'html5' => false,
                'input' => 'string',
                'widget' => 'single_text',
                'required' => false,
            ],
        );

        $builder->get('dateTo')->addModelTransformer(new DateTimeTransformer());
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['translation_prefix'] = $options['translation_prefix'];
    }
}
