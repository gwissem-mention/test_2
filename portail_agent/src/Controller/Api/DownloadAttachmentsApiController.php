<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\AttachmentDownload;
use App\Entity\Complaint;
use App\Entity\User;
use App\Logger\ApplicationTracesLogger;
use App\Logger\ApplicationTracesMessage;
use App\Repository\AttachmentDownloadRepository;
use App\Repository\ComplaintRepository;
use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class DownloadAttachmentsApiController extends AbstractController
{
    #[Route('/api/complaint/{complaintFrontId}/attachments', name: 'api_download_attachments', methods: ['GET'])]
    public function __invoke(
        string $complaintFrontId,
        ComplaintRepository $complaintRepository,
        FilesystemOperator $defaultStorage,
        LoggerInterface $apiLogger,
        ApplicationTracesLogger $applicationTraceslogger,
        Request $request,
        AttachmentDownloadRepository $attachmentDownloadRepository
    ): Response {
        if (!$this->isGranted('IS_AUTHENTICATED')) {
            $apiLogger->error(sprintf(
                'User not authenticated. Got status code %d',
                401
            ));

            return $this->json([], 401);
        }

        /** @var Complaint|null $complaint */
        $complaint = $complaintRepository->findOneBy(['frontId' => $complaintFrontId]);

        if (null === $complaint) {
            $apiLogger->error(sprintf(
                'Complaint %s not found. Got status code %d',
                $complaintFrontId,
                404
            ));

            return $this->json([], 404);
        }

        if (!$this->isGranted('COMPLAINT_VIEW', $complaint)) {
            $apiLogger->error(sprintf(
                'Complaint %s, access forbidden. Got status code %d',
                $complaintFrontId,
                403
            ));

            return $this->json([], 403);
        }

        $zipName = $complaintFrontId.'/'.$complaintFrontId.'.zip';

        if (!$defaultStorage->fileExists($zipName)) {
            $apiLogger->error(sprintf(
                'Complaint %s attachments zip not found. Got status code %d',
                $complaintFrontId,
                204
            ));

            return $this->json([], 204);
        }

        $apiLogger->info(sprintf(
            'Complaint %s attachments zip downloaded successfully. Got status code %d',
            $complaintFrontId,
            200
        ));

        /** @var User $user */
        $user = $this->getUser();

        $applicationTraceslogger->log(ApplicationTracesMessage::message(
            ApplicationTracesMessage::DOWNLOAD,
            $complaint->getDeclarationNumber(),
            $user->getNumber(),
            $request->getClientIp())
        );

        $attachmentDownloadRepository->save(new AttachmentDownload($complaint), true);

        return new StreamedResponse(function () use ($zipName, $defaultStorage) {
            /** @var resource $outputStream */
            $outputStream = fopen('php://output', 'wb');
            $fileStream = $defaultStorage->readStream($zipName);
            stream_copy_to_stream($fileStream, $outputStream);
        }, 200, [
            'Content-Type' => $defaultStorage->mimeType($zipName),
            'Content-Disposition' => ResponseHeaderBag::DISPOSITION_INLINE,
        ]);
    }
}
