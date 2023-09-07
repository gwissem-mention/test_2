<?php

declare(strict_types=1);

namespace App\Complaint\Messenger\ComplaintObjectsFileZip;

use App\Entity\Complaint;
use App\Entity\FactsObjects\AbstractObject;
use App\Repository\ComplaintRepository;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ComplaintObjectsFileZipHandler
{
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly FilesystemOperator $defaultStorage,
        private readonly ComplaintRepository $complaintRepository
    ) {
    }

    public function __invoke(ComplaintObjectsFileZipMessage $complaintObjectsFileZipMessage): void
    {
        /** @var Complaint $complaint */
        $complaint = $this->complaintRepository->find($complaintObjectsFileZipMessage->getComplaintId());

        if (!$complaint->hasObjectsWithFiles()) {
            return;
        }

        $zip = new \ZipArchive();
        $tmpZipName = $this->filesystem->tempnam(sys_get_temp_dir(), 'sb_');
        $zip->open($tmpZipName, \ZipArchive::CREATE);

        /** @var AbstractObject $object */
        foreach ($complaint->getObjectsWithFiles() as $object) {
            foreach ($object->getFiles() ?? [] as $file) {
                $fileStream = $this->defaultStorage->readStream($file);
                $tmpFileName = $this->filesystem->tempnam(sys_get_temp_dir(), 'sb_');
                /** @var resource $tmpFile */
                $tmpFile = fopen($tmpFileName, 'wb+');
                stream_copy_to_stream($fileStream, $tmpFile);

                $zip->addFile($tmpFileName, basename($file));
                fclose($tmpFile);
            }
        }
        $zip->close();

        $this->defaultStorage->writeStream(
            $complaint->getFrontId().'/'.$complaint->getFrontId().'.zip',
            fopen($tmpZipName, 'rb')
        );
    }
}
