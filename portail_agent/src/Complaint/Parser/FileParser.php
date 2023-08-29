<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

use League\Flysystem\FilesystemOperator;

/**
 * @phpstan-type JsonFile object{name: string }
 */
class FileParser
{
    public function __construct(
        private readonly FilesystemOperator $defaultStorage,
        private readonly string $complaintsBasePath,
    ) {
    }

    /**
     * @param JsonFile $fileInput
     *
     * @throws \League\Flysystem\FilesystemException
     */
    public function parse(object $fileInput, string $complaintFrontId): string
    {
        $newPath = $complaintFrontId.'/'.$fileInput->name;
        $oldPath = $this->complaintsBasePath.'/'.$complaintFrontId.'/'.$fileInput->name;

        $this->defaultStorage->writeStream(
            $newPath,
            fopen($oldPath, 'rb')
        );

        return $newPath;
    }
}
