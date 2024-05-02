<?php

declare(strict_types=1);

namespace App\Backoffice\Form\MyAccount;

use App\Backoffice\Form\User\UserPasswordType;
use Ibexa\User\Validator\Constraints\UserPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ChangePasswordType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('translation_domain', 'backoffice');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'current_password',
            PasswordType::class,
            [
                'required' => true,
                'constraints' => [
                    new UserPassword(['message' => 'backoffice.change_password.current_password.invalid']),
                ],
            ],
        );

        $builder->add(
            'password',
            UserPasswordType::class,
            [
                'required' => true,
            ],
        );
    }
}
