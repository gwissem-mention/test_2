<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Complaint;
use App\Oodrive\ApiFileUploader;
use App\Oodrive\ApiFileUploaderStatusEnum;
use App\Repository\ComplaintRepository;
use App\Repository\UploadReportRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File as FileConstraints;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UploadReportApiController extends AbstractController
{
    #[Route('/api/complaint/{declarationNumber}/lrp-upload', name: 'api_send_report', methods: ['PUT'])]
    public function __invoke(
        Request $request,
        ValidatorInterface $validator,
        ComplaintRepository $complaintRepository,
        TranslatorInterface $translator,
        LoggerInterface $logger,
        ApiFileUploader $apiFileUploader,
        string $declarationNumber,
        UploadReportRepository $uploadReportRepository
    ): JsonResponse {
        if (!$this->isGranted('IS_AUTHENTICATED')) {
            $logger->error('User not authenticated.');

            return new JsonResponse([], 401);
        }

        $complaint = $complaintRepository->findOneByDeclarationNumber($declarationNumber);

        if (!$complaint) {
            $logger->error(sprintf(
                'Complaint with declaration number %s is not found',
                $declarationNumber
            ));

            return new JsonResponse(['message' => 'Complaint not found.'], 404);
        }

        if (!in_array($complaint->getStatus(), [Complaint::STATUS_ONGOING_LRP, Complaint::STATUS_CLOSED])) {
            $logger->error(sprintf(
                'Access forbidden for this complaint %d with status %s',
                $declarationNumber,
                $complaint->getStatus()
            ));

            return $this->json(['message' => 'Access forbidden for this complaint.'], 403);
        }

        $uploadType = (string) $request->headers->get('X-UPLOAD-TYPE');
        if (!in_array($uploadType, ['PV', 'RECEPISSE'])) {
            $logger->error(sprintf(
                'Invalid upload type: %s',
                $uploadType
            ));

            return $this->json(['message' => 'Invalid upload type.'], 400);
        }

        if (false === $request->headers->has('timestamp')) {
            $logger->error('No timestamp header');

            return $this->json(['message' => 'No timestamp header'], 400);
        }

        if (false === $request->headers->has('size')) {
            $logger->error('No size header');

            return $this->json(['message' => 'No size header'], 400);
        }

        if (false === $request->headers->has('originName')) {
            $logger->error('No originName header');

            return $this->json(['message' => 'No originName header'], 400);
        }

        /** @var UploadedFile $requestFile */
        $requestFile = $request->files->all()['file'] ?? [];
        $fileConstraints = [
            new FileConstraints([
                'mimeTypes' => [
                    'image/jpeg',
                    'image/png',
                    'application/pdf',
                ],
                'mimeTypesMessage' => $translator->trans('pel.file.must.be.image.or.pdf'),
            ]),
        ];
        $violations = $validator->validate($requestFile, $fileConstraints);
        if ($violations->count() > 0) {
            return $this->json($violations, 400);
        }

        $uploadStatus = $apiFileUploader->upload(
            $complaint,
            $requestFile,
            $uploadType,
            (int) $request->headers->get('timestamp'),
            (int) $request->headers->get('size'),
            (string) $request->headers->get('originName')
        );

        switch ($uploadStatus) {
            case ApiFileUploaderStatusEnum::IGNORED:
                $message = 'The exact same file already exist.';
                $statusCode = 204;
                break;
            case ApiFileUploaderStatusEnum::REPLACED:
                $message = 'The file was successfully replaced.';
                $statusCode = 200;
                break;
            case ApiFileUploaderStatusEnum::UPLOADED:
                $message = 'The file has been uploaded successfully.';
                $statusCode = 201;
                break;
            default:
                $message = 'An error occurred while uploading the file.';
                $statusCode = 500;
        }

        return new JsonResponse(['message' => $message], $statusCode);
    }
}
