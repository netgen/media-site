<?php

declare(strict_types=1);

namespace App\Backoffice\Form\MyAccount;

use App\Validator\IbexaFieldValueValid;
use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

final class PersonalDetailsType extends AbstractType
{
    public function __construct(private ContentTypeService $contentTypeService) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('translation_domain', 'backoffice');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $contentType = $this->contentTypeService->loadContentTypeByIdentifier('user');

        $builder->add(
            'image',
            FileType::class,
            [
                'required' => false,
                'label' => 'form.my_account.personal_details.image',
                'constraints' => [
                    new Constraints\Image(),
                    new IbexaFieldValueValid([
                        'field' => 'image',
                        'contentType' => 'user',
                    ]),
                ],
            ],
        );

        $builder->add(
            'remove_image',
            CheckboxType::class,
            [
                'required' => false,
            ],
        );

        $builder->add(
            'first_name',
            TextType::class,
            [
                'required' => true,
                'label' => 'form.my_account.personal_details.first_name',
                'constraints' => [
                    new Constraints\NotBlank(),
                    new IbexaFieldValueValid([
                        'field' => 'first_name',
                        'contentType' => 'user',
                    ]),
                ],
            ],
        );

        $builder->add(
            'last_name',
            TextType::class,
            [
                'required' => true,
                'label' => 'form.my_account.personal_details.last_name',
                'constraints' => [
                    new Constraints\NotBlank(),
                    new IbexaFieldValueValid([
                        'field' => 'last_name',
                        'contentType' => 'user',
                    ]),
                ],
            ],
        );

        $builder->setDataMapper(new PersonalDetailsDataMapper());
    }
}
