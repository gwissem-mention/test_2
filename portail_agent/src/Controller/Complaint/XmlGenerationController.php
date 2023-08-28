<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Complaint\ComplaintWorkflowManager;
use App\Complaint\DTO\Objects\PreComplaintHistory;
use App\Complaint\Messenger\ComplaintObjectsFileZip\ComplaintObjectsFileZipMessage;
use App\Entity\Complaint;
use App\Entity\User;
use App\Generator\Complaint\ComplaintGeneratorInterface;
use App\Logger\ApplicationTracesMessage;
use App\Messenger\InformationCenter\InfocentreMessage;
use App\Referential\Entity\Unit;
use App\Referential\Repository\UnitRepository;
use App\Repository\ComplaintRepository;
use App\Repository\DQLComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class XmlGenerationController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/xml/{id}', name: 'complaint_xml', methods: ['GET'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintGeneratorInterface $generatorXml,
        ComplaintRepository $complaintRepository,
        ComplaintWorkflowManager $complaintWorkflowManager,
        DQLComplaintRepository $DQLComplaintRepository,
        MessageBusInterface $bus,
        UnitRepository $unitRepository,
    ): Response {
        $complaintWorkflowManager->sendToLRP($complaint);
        $complaintRepository->save($complaint, true);
        /** @var User $user */
        $user = $this->getUser();
        /** @var Unit $unit */
        $unit = $unitRepository->findOneBy(['serviceId' => $user->getServiceCode()]);

        $tmpFileName = (new Filesystem())->tempnam(sys_get_temp_dir(), 'sb_');
        $tmpFile = fopen($tmpFileName, 'wb+');

        if (!\is_resource($tmpFile)) {
            throw new \RuntimeException('Unable to create a temporary file.');
        }

        /** @var \SimpleXMLElement $xml */
        $xml = $generatorXml->generate($complaint, $unit);
        if (is_string($xml->asXML())) {
            fputs($tmpFile, $xml->asXML());
        }

        $DQLComplaintRepository->save(PreComplaintHistory::new(
            [
                'complaint' => $complaint,
                'prejudiceObject' => false !== $xml->Objet->asXML() ? $xml->Objet->asXML() : null,
                'file' => file_get_contents($tmpFileName),
                'unit' => $unit,
            ]
        ));
        /** @var string $unitCode */
        $unitCode = $complaint->getUnitToReassign();
        $unit = $unitRepository->findOneBy(['code' => $unitCode]);

        $bus->dispatch(new InfocentreMessage(ApplicationTracesMessage::SENT_TO_LRP, $complaint, $unit));
        $bus->dispatch(new ComplaintObjectsFileZipMessage((int) $complaint->getId()));
        fclose($tmpFile);

        return $this->redirectToRoute('complaint_summary', ['id' => $complaint->getId()]);
    }
}
