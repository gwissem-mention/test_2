<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    #[Route('/infos/plan-du-site', name: 'sitemap')]
    public function __invoke(): Response
    {
        return $this->render('pages/sitemap.html.twig');
    }
}