<?php

declare(strict_types=1);

namespace App\Thesaurus;

/**
 * @deprecated
 */
class MockedObjectCategoryThesaurusThesaurusProvider implements ObjectCategoryThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'pel.object.category.documents' => 1,
            'pel.object.category.payment.ways' => 2,
            'pel.object.category.mobile.phone' => 3,
            'pel.object.category.registered.vehicle' => 4,
            'pel.object.category.unregistered.vehicle' => 5,
            'pel.object.category.other' => 6,
        ];
    }
}
