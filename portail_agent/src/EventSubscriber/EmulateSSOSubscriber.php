<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Repository\UserRepository;
use App\Security\AgentAuthenticator;
use App\Security\SSOLessInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class EmulateSSOSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly bool $ssoIsEnabled, private readonly UserRepository $userRepository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => ['onKernelRequest', 2048],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($this->ssoIsEnabled) {
            return;
        }

        $request = $event->getRequest();

        if (null === $userId = $request->cookies->get(SSOLessInterface::COOKIE_NAME)) {
            return;
        }

        if (null === $user = $this->userRepository->find($userId)) {
            return;
        }
        $request->headers->set(AgentAuthenticator::HEADER_NUMBER, $user->getNumber());
        $request->headers->set(AgentAuthenticator::HEADER_INSTITUTION, $user->getInstitution()->name);
        $request->headers->set(AgentAuthenticator::HEADER_APPELLATION, $user->getAppellation());
        $request->headers->set(AgentAuthenticator::HEADER_SERVICE_CODE, $user->getServiceCode());
        $request->headers->set(AgentAuthenticator::HEADER_PROFILE, $user->isSupervisor() ? 'superviseur' : 'fsi');
    }
}
