<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\RightDelegation;
use App\Form\RightDelegationType;
use App\Repository\RightDelegationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RightDelegationController extends AbstractController
{
    #[Route('/delegation_droits', name: 'validate_delegation_rights', methods: ['GET', 'POST'])]
    public function __invoke(Request $request, RightDelegationRepository $delegationRepository): Response
    {
        if ($this->isGranted('ROLE_DELEGATED') || !$this->isGranted('ROLE_SUPERVISOR')) {
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette ressource.');
        }

        $rightDelegation = new RightDelegation();
        $form = $this->createForm(RightDelegationType::class, $rightDelegation);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isSubmitted()) {
            if (false === $form->isValid()) {
                return $this->json([
                    'form' => $this->renderView(
                        'common/_form.html.twig',
                        ['form' => $form->createView()]
                    ),
                ], 422);
            }

            $delegationRepository->save($rightDelegation, true);

            return new JsonResponse();
        }

        return $this->render('pages/delegation/index.html.twig', ['delegation_right_form' => $form->createView()]);
    }
}
