<?php

declare(strict_types=1);

namespace App\Backoffice\Form\MyAccount;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints;

final class ChangeEmailType extends AbstractType
{
    public function __construct(
        private readonly Security $security,
    ) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('translation_domain', 'backoffice');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'email_username',
            TextType::class,
            [
                'required' => true,
                'label' => 'form.my_account.user_credentials.email',
                'constraints' => [
                    new Constraints\NotBlank(),
                    new Constraints\Regex(['pattern' => '/^[^@]+$/', 'message' => 'backoffice.change_email.username.invalid']),
                ],
            ],
        );

        $builder->setDataMapper(new ChangeEmailDataMapper($this->security));
    }
}
