<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function __invoke(): Response
    {
        $complaints = [
            [
                'depositDate' => new \DateTime('01/01/2023'),
                'facts' => 'pel.robbery',
                'factsDate' => new \DateTime('12/28/2022'),
                'alert' => 'pel.alert',
                'meetingDate' => new \DateTime('01/04/2023'),
                'firstnameLastname' => 'Jean Dupont',
                'status' => 'pel.in.progress',
                'aOpjName' => 'Paul Braud',
                'declarationNumber' => '2023-00000678',
                'comments' => 'Lorem ipsum dolor sit amet',
            ],
            [
                'depositDate' => new \DateTime('11/03/2022'),
                'facts' => 'pel.degradation',
                'factsDate' => new \DateTime('11/01/2022'),
                'alert' => 'pel.alert',
                'meetingDate' => new \DateTime('11/11/2022'),
                'firstnameLastname' => 'Marie Vernier',
                'status' => 'pel.done',
                'aOpjName' => 'Michel Blanc',
                'declarationNumber' => '2022-12312312',
                'comments' => 'Lorem ipsum dolor sit amet',
            ],
        ];

        return $this->render('pages/index.html.twig', [
            'complaints' => $complaints,
        ]);
    }
}
