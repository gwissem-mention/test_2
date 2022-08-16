<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\JobType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    #[Route('/job', name: 'job')]
    public function __invoke(): Response
    {
        $form = $this->createForm(JobType::class);

        return $this->renderForm('job.html.twig', [
            'form' => $form,
        ]);
    }
}
