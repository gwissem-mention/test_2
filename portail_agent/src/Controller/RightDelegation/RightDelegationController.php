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

class RightDelegationController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/delegation_droits', name: 'validate_delegation_rights', methods: ['POST'])]
    public function __invoke(
        Request $request,
        RightDelegationRepository $delegationRepository,
        UserRepository $userRepository,
        NotificationFactory $notificationFactory
    ): Response {
        $this->denyAccessUnlessGranted(RightsDelegationVoter::DELEGATION_RIGHTS, $this->getUser());

        $rightDelegation = new RightDelegation();

        $form = $this->createForm(RightDelegationType::class, $rightDelegation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ?User $user */
            $user = $this->getUser();

            if (null !== $user) {
                $rightDelegation->setDelegatingAgent($user);
                /** @var User $agent */
                foreach ($rightDelegation->getDelegatedAgents() as $agent) {
                    $agent->setDelegationGained($rightDelegation);
                    $agent->addNotification(
                        $notificationFactory->createForDelegationRightsGained($user, $rightDelegation)
                    );
                }
                $delegationRepository->save($rightDelegation);
                $user->addRightDelegation($rightDelegation);
                $userRepository->save($user, true);

                return new JsonResponse();
            }

            $this->createNotFoundException(sprintf('User is not found !'));
        }

        return $this->json([
            'form' => $this->renderView(
                'common/_form.html.twig',
                ['form' => $form->createView()]
            ),
        ], 422);
    }
}
