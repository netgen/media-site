<?php

declare(strict_types=1);

namespace App\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('translation_domain', 'backoffice');
        $resolver->setDefault('method', Request::METHOD_GET);
        $resolver->setDefault('csrf_protection', false);

        $resolver->setRequired('translation_prefix');
        $resolver->setAllowedTypes('translation_prefix', 'string');

        $resolver->setRequired('show_date');
        $resolver->setAllowedTypes('show_date', 'bool');
        $resolver->setDefault('show_date', false);

        $resolver->setRequired('show_search');
        $resolver->setAllowedTypes('show_search', 'bool');
        $resolver->setDefault('show_search', false);

        $resolver->setRequired('selections');
        $resolver->setAllowedTypes('selections', 'array');
        $resolver->setDefault('selections', []);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'selections',
            SelectionsType::class,
            [
                'label' => false,
                'translation_prefix' => $options['translation_prefix'],
                'selections' => $options['selections'],
            ],
        );

        $builder->add(
            'date',
            DateRangeType::class,
            [
                'label' => false,
                'translation_prefix' => $options['translation_prefix'],
            ],
        );

        $builder->add(
            'searchText',
            Type\SearchType::class,
            [
                'required' => false,
            ],
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['translation_prefix'] = $options['translation_prefix'];
        $view->vars['show_date'] = $options['show_date'];
        $view->vars['show_search'] = $options['show_search'];
    }
}
