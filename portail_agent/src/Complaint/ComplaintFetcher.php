<?php

declare(strict_types=1);

namespace App\Complaint;

use App\Complaint\Exceptions\NoAffectedServiceException;
use App\Oodrive\ApiClientInterface;
use App\Oodrive\DTO\File;
use App\Oodrive\DTO\Folder;
use App\Oodrive\FolderRotation\FolderRotator;
use App\Oodrive\ParamsObject\SearchParamObject;
use App\Repository\ComplaintRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class ComplaintFetcher
{
    public function __construct(
        private readonly FolderRotator $folderRotator,
        private readonly ApiClientInterface $oodriveClient,
        private readonly Filesystem $filesystem,
        private readonly string $oodriveRootFolderId,
        private readonly string $oodriveFetchedFolderId,
        private readonly string $complaintsBasePath,
        private readonly ComplaintFileParser $complaintFileParser,
        private readonly ComplaintRepository $complaintRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function fetch(): int
    {
        $toFetchFolder = $this->oodriveClient->getFolder($this->oodriveRootFolderId);
        $complaintsFetchedCount = 0;
        $complaintFile = null;
        $toFetchFolderChildren = $this->oodriveClient->getChildrenFolders($toFetchFolder);
        foreach ($toFetchFolderChildren as $childFolder) {
            $emailFolders = $this->oodriveClient->getChildrenFolders($childFolder);
            foreach ($emailFolders as $emailFolder) {
                $complaintFolders = $this->oodriveClient->getChildrenFolders($emailFolder);
                foreach ($complaintFolders as $complaintFolder) {
                    $files = $this->oodriveClient->getChildrenFiles($complaintFolder);

                    try {
                        foreach ($files as $file) {
                            if (false === $file->isDir()) {
                                try {
                                    $this->logger->info(sprintf('Fetching file %s in folder %s', $file->getName(), $complaintFolder->getId()));
                                    $this->downloadFile($file, $complaintFolder->getName());
                                    $this->logger->info(sprintf('File %s fetched', $file->getName()));

                                    if ('plainte.json' === $file->getName()) {
                                        $this->logger->info(sprintf('Parsing file %s from folder %s', $file->getName(), $complaintFolder->getId()));
                                        $this->parseAndPersistComplaint($this->complaintsBasePath.'/'.$complaintFolder->getName().'/'.$file->getName());
                                        $this->logger->info(sprintf('File %s parsed', $file->getName()));
                                    }
                                } catch (\Exception $e) {
                                    // @TODO: Mark the complaint as not fetched? Send report Email?
                                    throw $e;
                                }
                            }
                        }
                        ++$complaintsFetchedCount;
                        $this->moveToFetchedFolder($complaintFolder, $emailFolder->getName());
                    } catch (NoAffectedServiceException $e) {
                        $this->logger->error(sprintf('No affected service found for file %s. Skipped.', $file->getName()));
                    }
                }
            }
        }

        return $complaintsFetchedCount;
    }

    private function downloadFile(File $file, string $complaintFolderName): void
    {
        $complaintPath = $this->complaintsBasePath.'/'.$complaintFolderName;

        if (!$this->filesystem->exists($complaintPath)) {
            $this->filesystem->mkdir($complaintPath);
        }

        $fileDownloaded = $this->oodriveClient->downloadFile($file);
        $this->filesystem->dumpFile($complaintPath.'/'.$file->getName(), $fileDownloaded->getContent());
    }

    private function moveToFetchedFolder(Folder $folder, string $email): void
    {
        $destinationEmailFolder = $this->getEmailFolder($email, $this->oodriveFetchedFolderId);
        $folder->setParentId($destinationEmailFolder->getId());

        $this->oodriveClient->updateFolder($folder);
    }

    private function getEmailFolder(string $email, string $rootFolderId): Folder
    {
        $searchParam = (new SearchParamObject())->type(['folder'])->folderId($rootFolderId)->q($email);

        $parentFolders = $this->oodriveClient->getChildrenFolders($this->oodriveClient->getFolder($rootFolderId));
        $parentFoldersId = array_map(static fn (Folder $folderData) => $folderData->getId(), $parentFolders);

        /** @var Folder[] $results */
        $results = $this->oodriveClient->search($searchParam);
        $results = array_filter($results, static fn (Folder $folder) => in_array($folder->getParentId(), $parentFoldersId));

        if (count($results) > 0) {
            return reset($results);
        }

        $folder = $this->folderRotator->getLeastUsedFolder($rootFolderId);

        return $this->oodriveClient->createFolder($email, $folder->getId());
    }

    private function parseAndPersistComplaint(string $complaintPath): void
    {
        /** @var string $complaintContent */
        $complaintContent = file_get_contents($complaintPath);

        $this->complaintRepository->save($this->complaintFileParser->parse($complaintContent), true);
    }
}
