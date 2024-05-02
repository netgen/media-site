<?php

declare(strict_types=1);

namespace App\Backoffice\Form\MyAccount;

use Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException;
use Ibexa\Contracts\Core\Repository\Values\User\User;
use Ibexa\Core\FieldType\Image\Value;
use Symfony\Component\Form\DataMapperInterface;
use Traversable;

use function iterator_to_array;
use function pathinfo;

use const PATHINFO_FILENAME;

final class PersonalDetailsDataMapper implements DataMapperInterface
{
    public function mapDataToForms($viewData, Traversable $forms): void
    {
        if (!$viewData instanceof User) {
            return;
        }

        $forms = iterator_to_array($forms);

        $forms['first_name']->setData(
            $viewData->getField('first_name')->value->text,
        );

        $forms['last_name']->setData(
            $viewData->getField('last_name')->value->text,
        );
    }

    public function mapFormsToData(Traversable $forms, &$viewData): void
    {
        $forms = iterator_to_array($forms);

        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile|null $image */
        $image = $forms['image']->getData();

        if ($image !== null) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->guessExtension();

            try {
                $imageValue = Value::fromString($forms['image']->getData()->getRealPath());
                $imageValue->fileName = $originalFilename . '.' . $extension;
            } catch (InvalidArgumentException) {
                $imageValue = null;
            }
        }

        $viewData = new PersonalDetailsData(
            $imageValue ?? null,
            $forms['remove_image']->getData(),
            $forms['first_name']->getData(),
            $forms['last_name']->getData(),
        );
    }
}
