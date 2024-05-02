<?php

declare(strict_types=1);

namespace App\Backoffice\Form\User;

use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

final class UserPasswordType extends AbstractType
{
    public function __construct(
        private ContentTypeService $contentTypeService,
    ) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $contentType = $this->contentTypeService->loadContentTypeByIdentifier('user');

        /** @var \Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition $userAccountFieldDefinition */
        $userAccountFieldDefinition = $contentType->getFieldDefinition('user_account');

        $resolver->setDefaults(
            [
                'type' => Type\PasswordType::class,
                'translation_domain' => 'backoffice',
                'invalid_message' => 'backoffice.user_password.invalid',
                'first_options' => [
                    'label' => 'form.user_password.password.label',
                ],
                'second_options' => [
                    'label' => 'form.user_password.repeat_password.label',
                ],
                'constraints' => fn (Options $options) => $this->getPasswordConstraints($userAccountFieldDefinition, $options),
            ],
        );
    }

    public function getParent(): string
    {
        return Type\RepeatedType::class;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    private function getPasswordConstraints(FieldDefinition $definition, Options $options): array
    {
        $config = $definition->validatorConfiguration['PasswordValueValidator'];

        $constraints = $options['required'] ?
            [new Constraints\NotBlank()] :
            [];

        if ($config['requireAtLeastOneUpperCaseCharacter'] ?? false) {
            $constraints[] = new Constraints\Regex(
                [
                    'pattern' => '/\p{Lu}/u',
                    'message' => 'backoffice.register_user.password.at_least_one_uppercase_character',
                ],
            );
        }

        if ($config['requireAtLeastOneLowerCaseCharacter'] ?? false) {
            $constraints[] = new Constraints\Regex(
                [
                    'pattern' => '/\p{Ll}/u',
                    'message' => 'backoffice.register_user.password.at_least_one_lowercase_character',
                ],
            );
        }

        if ($config['requireAtLeastOneNumericCharacter'] ?? false) {
            $constraints[] = new Constraints\Regex(
                [
                    'pattern' => '/\pN/u',
                    'message' => 'backoffice.register_user.password.at_least_one_numeric_character',
                ],
            );
        }

        if ($config['requireAtLeastOneNonAlphanumericCharacter'] ?? false) {
            $constraints[] = new Constraints\Regex(
                [
                    'pattern' => '/[^\p{Ll}\p{Lu}\pL\pN]/u',
                    'message' => 'backoffice.register_user.password.at_least_one_non_alphanumeric_character',
                ],
            );
        }

        if ($config['requireNotCompromisedPassword'] ?? false) {
            $constraints[] = new Constraints\NotCompromisedPassword();
        }

        if (($config['minLength'] ?? 0) > 0) {
            $constraints[] = new Constraints\Length(['min' => $config['minLength']]);
        }

        return $constraints;
    }
}
