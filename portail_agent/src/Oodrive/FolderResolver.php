<?php

declare(strict_types=1);

namespace App\Oodrive;

use App\Complaint\Exceptions\NoOodriveComplaintFolderException;
use App\Entity\Complaint;
use App\Oodrive\DTO\Folder;
use App\Oodrive\Exception\FolderCreationException;
use App\Oodrive\Exception\OodriveErrorsEnum;
use App\Oodrive\FolderRotation\FolderRotator;
use App\Oodrive\ParamsObject\SearchParamObject;

class FolderResolver
{
    public function __construct(
        private readonly FolderRotator $folderRotator,
        private readonly ApiClientInterface $oodriveClient,
        private readonly string $oodriveReportFolderName,
        private readonly string $oodriveRootFolderId,
    ) {
    }

    public function resolve(string $email, string $complaintNumber): Folder
    {
        $emailFolder = $this->getEmailFolder($email);

        return $this->oodriveClient->createFolder($complaintNumber, $emailFolder->getId());
    }

    public function resolveReportFolder(Complaint $complaint): Folder
    {
        if (null === $complaint->getOodriveFolder()) {
            throw new NoOodriveComplaintFolderException("No Oodrive folder for complaint {$complaint->getId()}");
        }

        try {
            $reportFolder = $this->oodriveClient->createFolder($this->oodriveReportFolderName, $complaint->getOodriveFolder());
        } catch (FolderCreationException $exception) {
            if (OodriveErrorsEnum::NAME_ALREADY_EXIST === $exception->getErrorCode()) {
                $complaintFolder = $this->oodriveClient->getFolder($complaint->getOodriveFolder());
                $complaintFolderChildren = $this->oodriveClient->getChildrenFolders($complaintFolder);
                foreach ($complaintFolderChildren as $child) {
                    if ($this->oodriveReportFolderName === $child->getName()) {
                        $reportFolder = $child;
                        break;
                    }
                }

                if (!isset($reportFolder)) {
                    throw $exception;
                }
            } else {
                throw $exception;
            }
        }

        return $reportFolder;
    }

    private function getEmailFolder(string $email): Folder
    {
        $searchParam = (new SearchParamObject())->type(['folder'])->folderId($this->oodriveRootFolderId)->q($email);

        $parentFolders = $this->oodriveClient->getChildrenFolders($this->oodriveClient->getFolder($this->oodriveRootFolderId));
        $parentFoldersId = array_map(static fn (Folder $folderData) => $folderData->getId(), $parentFolders);

        /** @var Folder[] $results */
        $results = $this->oodriveClient->search($searchParam);
        $results = array_filter($results, static fn (Folder $folder) => in_array($folder->getParentId(), $parentFoldersId));

        if (count($results) > 0) {
            return reset($results);
        }

        $folder = $this->folderRotator->getLeastUsedFolder($this->oodriveRootFolderId);

        return $this->oodriveClient->createFolder($email, $folder->getId());
    }
}
