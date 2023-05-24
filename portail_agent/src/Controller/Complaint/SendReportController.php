<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Factory\NotificationFactory;
use App\Form\DropZoneType;
use App\Report\SendReport;
use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class SendReportController extends AbstractController
{
    #[Route(path: '/plainte/envoi-pv/{id}', name: 'complaint_send_report', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintRepository $complaintRepository,
        NotificationFactory $notificationFactory,
        Request $request,
        MessageBusInterface $bus
    ): JsonResponse {
        $form = $this->createForm(DropZoneType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (false === $form->isValid()) {
                return $this->json([
                    'form' => $this->renderView(
                        'common/_form.html.twig',
                        ['form' => $form->createView()]
                    ),
                ], 422);
            }

            /** @var array<string, UploadedFile> $data */
            $data = $form->getData();
            $report = $data['file'];

            $bus->dispatch(new SendReport($report));

            $complaintRepository->save($complaint->setStatus(Complaint::STATUS_CLOSED), true);

            return new JsonResponse();
        }

        return $this->json([
        ], 422);
    }
}
