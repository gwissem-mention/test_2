<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Form\Model\AdditionalInformation\AdditionalInformationModel;
use App\Session\SessionHandler;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/porter-plainte/recapitulatif/pdf', name: 'complaint_summary_pdf', methods: ['GET'])]
class SummaryPDFController extends AbstractController
{
    public function __invoke(Pdf $knpSnappyPdf, SessionHandler $sessionHandler): Response
    {
        if (!$sessionHandler->getComplaint()?->getAdditionalInformation() instanceof AdditionalInformationModel) {
            return $this->redirectToRoute('home');
        }

        $html = $this->renderView('pages/summary_pdf.html.twig', [
            'complaint' => $sessionHandler->getComplaint(),
        ]);

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html, [
                'page-size' => 'A4',
                'lowquality' => false,
            ]),
            'recapitulatif.pdf'
        );
    }
}
