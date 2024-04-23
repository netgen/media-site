<?php

declare(strict_types=1);

namespace App\Form;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ExportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('migration_type', ChoiceType::class, [
            'choices' => [
                'Create' => 'create',
                'Update' => 'update',
            ],
            'required' => true,
            'expanded' => true,
            'multiple' => false,
        ])
        ->add('source_structure', ChoiceType::class, [
            'choices' => [
                'Content' => 'content',
                'Subtree' => 'subtree',
            ],
            'required' => true,
            'expanded' => true,
            'multiple' => false,
        ])->add(
            'source',
            ContentBrowserType::class,
            [
                'label' => 'Source',
                'item_type' => 'ibexa_content',
                'required' => true,
            ],
        );
    }
}
