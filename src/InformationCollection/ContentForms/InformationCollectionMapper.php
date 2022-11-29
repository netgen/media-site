<?php

declare(strict_types=1);

namespace App\InformationCollection\ContentForms;

use Ibexa\Contracts\ContentForms\Data\Content\FieldData;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Ibexa\Contracts\Core\Repository\Values\Content\Location;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition;
use Ibexa\Contracts\Core\Repository\Values\ValueObject;
use Netgen\InformationCollection\API\Value\InformationCollectionStruct;

final class InformationCollectionMapper
{
    /**
     * Maps a ValueObject from Ibexa content repository to a data usable as underlying form data (e.g. create/update struct).
     */
    public function mapToFormData(Content $content, Location $location, ContentType $contentType, ?string $languageCode = null): InformationCollectionStruct
    {
        $fields = $content->getFieldsByLanguage();

        $informationCollectionFields = [];

        /** @var FieldDefinition $fieldDef */
        foreach ($contentType->fieldDefinitions as $fieldDef) {
            if ($fieldDef->isInfoCollector) {
                $field = $fields[$fieldDef->identifier];

                $fieldData = new FieldData(
                    [
                        'fieldDefinition' => $fieldDef,
                        'field' => $field,
                    ]
                );

                $informationCollectionFields[] = $fieldData;
            }
        }

        return new InformationCollectionStruct(
            $content,
            $location,
            $contentType,
            $informationCollectionFields
        );
    }
}
