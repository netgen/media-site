<?php

declare(strict_types=1);

namespace App\InformationCollection\Form\Extension;

use Netgen\Bundle\InformationCollectionBundle\Ibexa\ContentForms\InformationCollectionType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class HoneypotExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [InformationCollectionType::class];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'sender_middle_name',
            TextType::class,
            [
                'label' => 'ngsite.collected_info.contact_form.middle_name',
                'attr' => [
                    'class' => 'sender-middle-name',
                    'placeholder' => 'ngsite.collected_info.contact_form.middle_name',
                    'tabIndex' => '-1',
                ],
                'translation_domain' => 'messages',
                'empty_data' => '',
            ]
        );
    }
}
