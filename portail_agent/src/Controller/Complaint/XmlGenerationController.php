<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Complaint\ComplaintWorkflowManager;
use App\Complaint\DTO\Objects\PreComplaintHistory;
use App\Entity\Complaint;
use App\Entity\User;
use App\Generator\Complaint\ComplaintGeneratorInterface;
use App\Referential\Entity\Service;
use App\Referential\Repository\ServiceRepository;
use App\Repository\ComplaintRepository;
use App\Repository\DQLComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class XmlGenerationController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/xml/{id}', name: 'complaint_xml', methods: ['GET'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintGeneratorInterface $generatorXml,
        ServiceRepository $serviceRepository,
        ComplaintRepository $complaintRepository,
        ComplaintWorkflowManager $complaintWorkflowManager,
        DQLComplaintRepository $DQLComplaintRepository
    ): Response {
        $complaintWorkflowManager->sendToLRP($complaint);
        $complaintRepository->save($complaint, true);
        /** @var User $user */
        $user = $this->getUser();
        /** @var Service $service */
        $service = $serviceRepository->findOneBy(['code' => $user->getServiceCode()]);

        $tmpFileName = (new Filesystem())->tempnam(sys_get_temp_dir(), 'sb_');
        $tmpFile = fopen($tmpFileName, 'wb+');

        if (!\is_resource($tmpFile)) {
            throw new \RuntimeException('Unable to create a temporary file.');
        }

        /** @var \SimpleXMLElement $xml */
        $xml = $generatorXml->generate($complaint, $service);
        if (is_string($xml->asXML())) {
            fputs($tmpFile, $xml->asXML());
        }

        $DQLComplaintRepository->save(PreComplaintHistory::new(
            [
                'complaint' => $complaint,
                'prejudiceObject' => false !== $xml->Objet->asXML() ? $xml->Objet->asXML() : null,
                'file' => file_get_contents($tmpFileName),
                'service' => $service,
            ]
        ));

        fclose($tmpFile);

        return $this->redirectToRoute('complaint_summary', ['id' => $complaint->getId()]);
    }
}
