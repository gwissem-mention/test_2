<?php

declare(strict_types=1);

namespace App\Complaint\Messenger\ComplaintFetch;

use App\Complaint\ComplaintFileParser;
use App\Complaint\Exceptions\NoAffectedServiceException;
use App\Oodrive\ApiClientInterface;
use App\Oodrive\DTO\File;
use App\Oodrive\DTO\Folder;
use App\Oodrive\FolderRotation\FolderRotator;
use App\Oodrive\ParamsObject\SearchParamObject;
use App\Repository\ComplaintRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ComplaintFetchHandler
{
    public function __construct(
        private readonly ApiClientInterface $oodriveClient,
        private readonly Filesystem $filesystem,
        private readonly string $oodriveFetchedFolderId,
        private readonly string $complaintsBasePath,
        private readonly ComplaintFileParser $complaintFileParser,
        private readonly ComplaintRepository $complaintRepository,
        private readonly LoggerInterface $logger,
        private readonly FolderRotator $folderRotator,
    ) {
    }

    public function __invoke(ComplaintFetchMessage $message): void
    {
        try {
            $complaintFolder = $message->getComplaintFolder();

            $files = $this->oodriveClient->getChildrenFiles($complaintFolder);

            try {
                foreach ($files as $file) {
                    if (false === $file->isDir()) {
                        try {
                            $this->logger->info(sprintf('Fetching file %s in folder %s', $file->getName(), $complaintFolder->getId()));
                            $this->downloadFile($file, $complaintFolder->getName());
                            $this->logger->info(sprintf('File %s fetched', $file->getName()));
                        } catch (\Exception $e) {
                            // @TODO: Mark the complaint as not fetched? Send report Email?
                            throw $e;
                        }
                    }
                }

                $this->logger->info(sprintf('Parsing file "plainte.json" from folder %s', $complaintFolder->getId()));
                $this->parseAndPersistComplaint($this->complaintsBasePath.'/'.$complaintFolder->getName().'/plainte.json', $complaintFolder->getId());
                $this->logger->info(sprintf('File "plainte.json" from folder %s parsed', $complaintFolder->getId()));

                $this->moveToFetchedFolder($complaintFolder, $message->getEmailFolder()->getName());
                $this->filesystem->remove($this->complaintsBasePath.'/'.$complaintFolder->getName());
            } catch (NoAffectedServiceException $e) {
                $this->logger->error(sprintf('No affected service found for file %s. Skipped.', $file->getName()));
            }
        } catch (\Exception $e) {
            $this->logger->error(sprintf('Error fetching complaint %s. Error: %s', $message->getComplaintFolder()->getName(), $e->getMessage()));
        }
    }

    private function parseAndPersistComplaint(string $complaintPath, string $complaintFolderId): void
    {
        /** @var string $complaintContent */
        $complaintContent = file_get_contents($complaintPath);

        $this->complaintRepository->save(
            $this->complaintFileParser->parse($complaintContent)->setOodriveFolder($complaintFolderId),
            true
        );
    }

    private function moveToFetchedFolder(Folder $folder, string $email): void
    {
        $destinationEmailFolder = $this->getEmailFolder($email, $this->oodriveFetchedFolderId);
        $folder->setParentId($destinationEmailFolder->getId());

        $this->oodriveClient->updateFolder($folder);
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
}
