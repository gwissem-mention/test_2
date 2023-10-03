<?php

declare(strict_types=1);

namespace App\Twig;

use App\Referential\Entity\DocumentType;
use App\Referential\Repository\DocumentTypeRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DocumentTypeExtension extends AbstractExtension
{
    public function __construct(private readonly DocumentTypeRepository $documentTypeRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('document_type_label', [$this, 'getLabel']),
        ];
    }

    public function getLabel(?int $documentTypeId): string
    {
        if (null === $documentTypeId) {
            return '';
        }

        $documentType = $this->documentTypeRepository->find($documentTypeId);

        return $documentType instanceof DocumentType ? $documentType->getLabel() : '';
    }
}
