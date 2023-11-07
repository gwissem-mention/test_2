<?php

declare(strict_types=1);

namespace App\Controller\RightDelegation;

use App\Entity\RightDelegation;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Form\RightDelegationType;
use App\Repository\RightDelegationRepository;
use App\Repository\UserRepository;
use App\Security\Voter\RightsDelegationVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RightDelegationModificationController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/delegation_droits/modification/{id}', name: 'modify_delegation_rights', methods: ['POST'])]
    public function __invoke(
        RightDelegation $rightDelegation,
        Request $request,
        RightDelegationRepository $delegationRepository,
        UserRepository $userRepository,
        NotificationFactory $notificationFactory
    ): Response {
        $this->denyAccessUnlessGranted(RightsDelegationVoter::DELEGATION_RIGHTS, $this->getUser());

        $form = $this->createForm(RightDelegationType::class, $rightDelegation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            foreach ($userRepository->findBy(['delegationGained' => $rightDelegation->getId()]) as $oldDelegatedAgent) {
                $oldDelegatedAgent->setDelegationGained(null);
            }

            /** @var User $agent */
            foreach ($rightDelegation->getDelegatedAgents() as $agent) {
                $agent->setDelegationGained($rightDelegation);
                $agent->addNotification(
                    $notificationFactory->createForDelegationRightsGained($user, $rightDelegation)
                );
            }

            $delegationRepository->save($rightDelegation);
            $userRepository->save($user, true);

            return new JsonResponse();
        }

        return $this->json([
            'form' => $this->renderView(
                'common/_form.html.twig',
                ['form' => $form->createView()]
            ),
        ], 422);
    }
}
