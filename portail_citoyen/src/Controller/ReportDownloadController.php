<?php

declare(strict_types=1);

namespace App\Controller;

use App\Oodrive\ApiClientInterface;
use App\Oodrive\DTO\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ReportDownloadController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/telecharger-pv/{id}/{name}', name: 'download_report')]
    public function __invoke(string $id, string $name, ApiClientInterface $oodriveApi): Response
    {
        $file = $oodriveApi->downloadFile(new File(['id' => $id, 'name' => $name, 'isDir' => false]));

        $response = new Response($file->getContent());

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $name,
            (string) preg_replace('#^.*\.#', md5($name).'.', $name)
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
