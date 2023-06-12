<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Entity\User;
use App\Generator\Complaint\ComplaintGeneratorInterface;
use App\Referential\Entity\Service;
use App\Referential\Repository\ServiceRepository;
use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class XmlGenerationController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/xml/{id}', name: 'complaint_xml', methods: ['GET'])]
    public function __invoke(Complaint $complaint, ComplaintGeneratorInterface $generatorXml, ServiceRepository $serviceRepository, ComplaintRepository $complaintRepository): Response
    {
        $complaintRepository->save($complaint->setStatus(Complaint::STATUS_ONGOING_LRP), true);
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

        $response = new BinaryFileResponse($tmpFileName);
        $response->headers->set('Content-type', 'application/xml');
        $response->setCharset('iso-8859-1');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $complaint->getDeclarationNumber().'.xml');

        fclose($tmpFile);

        return $response;
    }
}
