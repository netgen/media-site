<?php

declare(strict_types=1);

namespace App\Validator;

use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException;
use Ibexa\Core\FieldType\FieldTypeRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use function count;

final class IbexaFieldValueValidValidator extends ConstraintValidator
{
    public function __construct(
        private readonly FieldTypeRegistry $fieldTypeRegistry,
        private readonly ContentTypeService $contentTypeService,
    ) {}

    public function validate($value, Constraint $constraint): void
    {
        /** @var \App\Validator\IbexaFieldValueValid $constraint */
        $fieldIdentifier = $constraint->field;

        $contentType = $this->contentTypeService->loadContentTypeByIdentifier(
            $constraint->contentType,
        );

        /** @var \Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition $fieldDefinition */
        $fieldDefinition = $contentType->getFieldDefinition($fieldIdentifier);
        $fieldType = $this->fieldTypeRegistry->getFieldType($fieldDefinition->fieldTypeIdentifier);

        try {
            if ($value instanceof UploadedFile) {
                $value = $value->getRealPath();
            }

            $fieldValue = $fieldType->acceptValue($value);
            $fieldErrors = $fieldType->validate($fieldDefinition, $fieldValue);

            if (count($fieldErrors) > 0) {
                foreach ($fieldErrors as $fieldError) {
                    /** @var \Ibexa\Contracts\Core\Repository\Values\Translation\Plural $errorMessage */
                    $errorMessage = $fieldError->getTranslatableMessage();
                    $this->context->buildViolation((string) $errorMessage)
                        ->atPath($fieldIdentifier)
                        ->addViolation();
                }
            }
        } catch (InvalidArgumentException $e) {
            $this->context->buildViolation($e->getMessage())
                ->atPath($fieldIdentifier)
                ->addViolation();
        }
    }
}
