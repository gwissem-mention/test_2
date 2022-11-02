<?php

declare(strict_types=1);

namespace App\Controller\Identity;

use App\Session\FranceConnectHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/identite', name: 'identity')]
class IndexController extends AbstractController
{
    public function __invoke(
        Request $request,
        FranceConnectHandler $franceConnectHandler
    ): Response {
        if ('1' === $request->query->get('france_connected')) {
            $franceConnectHandler->set(
                'Michel',
                'DUPONT',
                '1967-03-02',
                'male',
                '75056',
                'FR',
                'michel.dupont@example.com'
            );
        } elseif ('0' === $request->query->get('france_connected')) {
            $franceConnectHandler->clear();
        }

        return $this->render('complaints/pages/identity.html.twig');
    }
}
