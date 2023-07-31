<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

class PreBulkUploadFile
{
    public const NAME = 'oodrive.file.pre_bulk_upload';

    public function __construct(
        private readonly string $parentId,
    ) {
    }

    public function getParentId(): string
    {
        return $this->parentId;
    }
}
