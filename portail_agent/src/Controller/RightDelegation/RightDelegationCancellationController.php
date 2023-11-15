<?php

declare(strict_types=1);

namespace App\Controller\RightDelegation;

use App\Entity\RightDelegation;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Repository\RightDelegationRepository;
use App\Repository\UserRepository;
use App\Security\Voter\RightsDelegationVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RightDelegationCancellationController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/delegation_droits/annulation/{id}', name: 'cancel_delegation_rights', methods: ['POST'])]
    public function __invoke(
        RightDelegation $rightDelegation,
        Request $request,
        RightDelegationRepository $delegationRepository,
        UserRepository $userRepository,
        NotificationFactory $notificationFactory
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $this->denyAccessUnlessGranted(RightsDelegationVoter::DELEGATION_RIGHTS, $user);
        $delegatedAgents = $userRepository->findBy(['delegationGained' => $rightDelegation->getId()]);

        foreach ($delegatedAgents as $delegatedAgent) {
            $user->removeRightDelegation($rightDelegation);
            $rightDelegation->removeDelegatedAgent($delegatedAgent);
            $delegatedAgent->removeRole('ROLE_DELEGATED');
        }

        $delegationRepository->remove($rightDelegation);

        return new JsonResponse();
    }
}
