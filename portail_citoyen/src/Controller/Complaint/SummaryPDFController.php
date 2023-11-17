<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Session\ComplaintModel;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: '/porter-plainte/recapitulatif/pdf', name: 'complaint_summary_pdf', methods: ['POST'])]
class SummaryPDFController extends AbstractController
{
    public function __invoke(Pdf $knpSnappyPdf, Request $request, SerializerInterface $serializer): Response
    {
        if (!$request->request->has('complaint')) {
            return $this->redirectToRoute('home');
        }

        $html = $this->renderView('pages/summary_pdf.html.twig', [
            'complaint' => $serializer->deserialize($request->request->get('complaint'), ComplaintModel::class, 'json'),
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
