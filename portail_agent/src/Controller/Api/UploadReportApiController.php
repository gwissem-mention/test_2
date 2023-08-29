<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Complaint\ComplaintWorkflowManager;
use App\Complaint\Messenger\SendReport\SendReportMessage;
use App\Entity\Complaint;
use App\Entity\User;
use App\Logger\ApplicationTracesLogger;
use App\Logger\ApplicationTracesMessage;
use App\Messenger\InformationCenter\InfocentreMessage;
use App\Referential\Repository\UnitRepository;
use App\Repository\ComplaintRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\File as FileConstraints;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UploadReportApiController extends AbstractController
{
    #[Route(path: '/api/envoi-pv', name: 'api_send_report', methods: ['POST'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        Filesystem $filesystem,
        ValidatorInterface $validator,
        MessageBusInterface $bus,
        ComplaintWorkflowManager $complaintWorkflowManager,
        ComplaintRepository $complaintRepository,
        UserRepository $userRepository,
        UnitRepository $unitRepository,
        ApplicationTracesLogger $logger,
        TranslatorInterface $translator
    ): JsonResponse {
        if (!empty($request->request->get('url')) && $request->files->count() > 0) {
            /** @var UploadedFile[] $requestFiles */
            $requestFiles = $request->files;
            $files = [];
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

            foreach ($requestFiles as $file) {
                $violations = $validator->validate($file, $fileConstraints);
                if ($violations->count() > 0) {
                    return $this->json($violations, 400);
                }
                $files[] = $file;
            }

            /** @var Complaint $complaint */
            $complaint = $complaintRepository->findOneBy(['declarationNumber' => $request->request->get('url')]);
            /** @var User $user */
            $user = $complaint->getAssignedTo();
            $unit = $unitRepository->findOneBy(['code' => (string) $complaint->getUnitAssigned()]);

            $bus->dispatch(new SendReportMessage($files, (int) $complaint->getId()));
            $complaintWorkflowManager->closeAfterSendingTheReport($complaint);
            $complaintRepository->save($complaint->setClosedAt(new \DateTimeImmutable()), true);
            $logger->log(ApplicationTracesMessage::message(
                ApplicationTracesMessage::SENDING_DOCUMENTS,
                $complaint->getDeclarationNumber(),
                $user->getNumber(),
                $request->getClientIp()
            ), $user);
            $bus->dispatch(new InfocentreMessage(ApplicationTracesMessage::VALIDATION, $complaint, $unit));

            return $this->json($files, 201);
        }

        return $this->json([], 400);
    }
}
