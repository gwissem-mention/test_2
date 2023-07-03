<?php

declare(strict_types=1);

namespace App\Controller;

use App\Oodrive\ApiClientInterface;
use App\Oodrive\DTO\File;
use App\Oodrive\DTO\ReportFolder;
use App\Oodrive\ReportsFetcher\ReportsFetcherInterface;
use App\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyComplaintsReportsController extends AbstractController
{
    #[Route('/mes-pv-de-plaintes', name: 'my_complaints_reports')]
    public function __invoke(ApiClientInterface $oodriveClient, ReportsFetcherInterface $reportsFetcher, string $oodriveFetchedZIPName): Response
    {
        $reports = [];
        $user = $this->getUser();

        if ($user instanceof User) {
            $reports = $reportsFetcher->fetch($user->getEmail());

            if (1 === count($reports)) {
                $firstReport = $reports[0];
                $firstReportFiles = $firstReport->getFiles();
                $fileId = (1 === count($firstReportFiles)) ? $firstReportFiles[0]->getId() : $firstReport->getId();
                $fileName = (1 === count($firstReportFiles)) ? $firstReportFiles[0]->getName() : $oodriveFetchedZIPName;
                $file = $oodriveClient->downloadFile(new File(['id' => $fileId, 'name' => $fileName, 'isDir' => false]));

                $response = new Response($file->getContent());
                $response->headers->set('Content-Disposition', HeaderUtils::makeDisposition(
                    HeaderUtils::DISPOSITION_ATTACHMENT,
                    $fileName,
                    (string) preg_replace('#^.*\.#', md5($fileName).'.', $fileName)
                ));

                return $response;
            }

            // Order by creation date DESC
            usort($reports, static fn (ReportFolder $a, ReportFolder $b) => $b->getCreationDate() <=> $a->getCreationDate());
        }

        return $this->render('pages/my_complaints_reports.html.twig', [
            'reports' => $reports,
        ]);
    }
}
