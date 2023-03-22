<?php

declare(strict_types=1);

namespace App\Twig;

use App\Enum\DocumentType;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DocumentTypeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('document_type_label', [$this, 'getLabel']),
        ];
    }

    public function getLabel(?int $value = null): ?string
    {
        return DocumentType::getLabel($value);
    }
}
