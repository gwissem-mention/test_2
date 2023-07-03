<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Complaint\ComplaintWorkflowException;
use App\Complaint\ComplaintWorkflowManager;
use App\Complaint\Messenger\SendReport\SendReportMessage;
use App\Entity\Complaint;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Form\Complaint\SendReportType;
use App\Logger\ApplicationTracesLogger;
use App\Logger\ApplicationTracesMessage;
use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SendReportController extends AbstractController
{
    /**
     * @throws ComplaintWorkflowException
     */
    #[IsGranted('COMPLAINT_VIEW', subject: 'complaint')]
    #[Route(path: '/plainte/envoi-pv/{id}', name: 'complaint_send_report', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintRepository $complaintRepository,
        NotificationFactory $notificationFactory,
        Request $request,
        MessageBusInterface $bus,
        ComplaintWorkflowManager $complaintWorkflowManager,
        ApplicationTracesLogger $logger,
        ValidatorInterface $validator
    ): JsonResponse {
        $form = $this->createForm(SendReportType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var array<string, array<UploadedFile>> $data */
            $data = $form->getData();
            $files = $data['files'];

            $fileConstraint = new File([
                'mimeTypes' => [
                    'image/jpeg',
                    'image/png',
                    'application/pdf',
                ],
                'mimeTypesMessage' => 'pel.file.must.be.image.or.pdf',
            ]);

            foreach ($files as $file) {
                $violations = $validator->validate($file, $fileConstraint);
                if ($violations->count() > 0) {
                    foreach ($violations as $violation) {
                        $form->addError(new FormError((string) $violation->getMessage()));
                    }
                }
            }

            if (false === $form->isValid()) {
                return $this->json([
                    'form' => $this->renderView(
                        'common/_form.html.twig',
                        ['form' => $form->createView()]
                    ),
                ], 422);
            }
            /** @var User $user */
            $user = $this->getUser();

            $bus->dispatch(new SendReportMessage($files, (int) $complaint->getId()));
            $complaintWorkflowManager->close($complaint);
            $complaintRepository->save($complaint->setClosedAt(new \DateTimeImmutable()), true);
            $logger->log(ApplicationTracesMessage::message(
                ApplicationTracesMessage::SENDING_DOCUMENTS,
                $complaint->getDeclarationNumber(),
                $user->getNumber(),
                $request->getClientIp()
            ));

            return new JsonResponse();
        }

        return $this->json([
        ], 422);
    }
}
