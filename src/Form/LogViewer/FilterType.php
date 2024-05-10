<?php

declare(strict_types=1);

namespace App\Form\LogViewer;

use App\Enums\Log\Module;
use App\Enums\Log\Severity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('translation_domain', 'log_viewer');
        $resolver->setDefault('method', Request::METHOD_GET);
        $resolver->setDefault('csrf_protection', false);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'message',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'log_viewer.message',
                ],
            );

        $builder
            ->add(
                'severity',
                EnumType::class,
                [
                    'class' => Severity::class,
                    'label' => 'log_viewer.severity',
                    'required' => false,
                ],
            );

        $builder
            ->add(
                'module',
                EnumType::class,
                [
                    'class' => Module::class,
                    'required' => false,
                    'label' => 'log_viewer.module',
                ],
            );

        $builder
            ->add(
                'email',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'log_viewer.email',
                ],
            );

        $builder
            ->add(
                'dateFrom',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'input' => 'string',
                    'required' => false,
                    'label' => 'log_viewer.date_from',
                ],
            );

        $builder->get('dateFrom')->addModelTransformer(new DateTimeTransformer());

        $builder
            ->add(
                'dateTo',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'input' => 'string',
                    'required' => false,
                    'label' => 'log_viewer.date_to',
                ],
            );

        $builder->get('dateTo')->addModelTransformer(new DateTimeTransformer());

        $builder
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'log_viewer.filter.button.submit',
                ],
            );

        $builder
            ->add(
                'export',
                SubmitType::class,
                [
                    'label' => 'log_viewer.filter.button.export',
                ],
            );
    }
}
