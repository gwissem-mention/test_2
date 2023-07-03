<?php

declare(strict_types=1);

namespace App\Oodrive\ReportsFetcher;

use App\Oodrive\ApiClientInterface;
use App\Oodrive\DTO\Folder;
use App\Oodrive\DTO\ReportFolder;
use App\Oodrive\ParamsObject\SearchParamObject;

class ReportsFetcher implements ReportsFetcherInterface
{
    public function __construct(
        readonly private ApiClientInterface $oodriveApi,
        readonly private string $oodriveFetchedFolderId,
        readonly private string $oodriveReportFolderName
    ) {
    }

    /**
     * @return array<ReportFolder>
     */
    public function fetch(string $email): array
    {
        $complaintsFolders = $this->oodriveApi->search((new SearchParamObject())->type(['folder'])->q($email));
        $reports = [];
        foreach ($complaintsFolders as $complaintsFolder) {
            if ($complaintsFolder instanceof Folder && $email === $complaintsFolder->getName()) {
                $parentFolder = $this->oodriveApi->getFolder($complaintsFolder->getParentId());
                if ($parentFolder->getParentId() === $this->oodriveFetchedFolderId) {
                    foreach ($this->oodriveApi->getChildrenFolders($complaintsFolder) as $childrenFolder) {
                        foreach ($this->oodriveApi->getChildrenFolders($childrenFolder) as $reportFolder) {
                            if ($this->oodriveReportFolderName === $reportFolder->getName()) {
                                $reports[] = new ReportFolder(
                                    $reportFolder->getId(),
                                    $childrenFolder->getCreationDate(),
                                    $this->oodriveApi->getChildrenFiles($reportFolder)
                                );
                            }
                        }
                    }
                }
            }
        }

        return $reports;
    }
}
