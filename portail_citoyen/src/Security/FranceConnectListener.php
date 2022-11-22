<?php

namespace App\Security;

use Symfony\Bundle\SecurityBundle\Security\FirewallConfig;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\FirewallMapInterface;

#[AsEventListener(RequestEvent::class, method: 'redirectForLogin')]
#[AsEventListener(RequestEvent::class, method: 'redirectAfterRemoteLogout')]
#[AsEventListener(LogoutEvent::class, method: 'storeIdToken', priority: 1000)]
#[AsEventListener(LogoutEvent::class, method: 'redirectOnLogout', priority: -1000)]
class FranceConnectListener
{
    public const LOGOUT_CALLBACK_ROUTE = 'france_connect_logout_callback';

    private ?string $idToken = null;

    /**
     * @param array<string, string> $logoutTargetPathMap
     */
    public function __construct(
        private Security $security,
        private UrlGeneratorInterface $urlGenerator,
        private FirewallMapInterface $firewallMap,
        private string $baseUri,
        private array $logoutTargetPathMap,
    ) {
    }

    public function redirectForLogin(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (
            null === $this->security->getUser() &&
            'complaint' === $event->getRequest()->attributes->get('_route')
            && 1 === $request->query->getInt('france_connected')
        ) {
            throw new AuthenticationException('authentication needed');
        }
    }

    public function redirectAfterRemoteLogout(RequestEvent $event): void
    {
        if (self::LOGOUT_CALLBACK_ROUTE === $event->getRequest()->attributes->get('_route')) {
            if (!$this->firewallMap instanceof FirewallMap) {
                throw new \LogicException(sprintf('firewallMap must be an instance of %s', FirewallMap::class));
            }

            $firewallConfig = $this->firewallMap->getFirewallConfig($event->getRequest());
            if (!$firewallConfig instanceof FirewallConfig) {
                throw new \LogicException(sprintf('Can\t find firewall config for route %s', self::LOGOUT_CALLBACK_ROUTE));
            }

            if (!isset($this->logoutTargetPathMap[$firewallConfig->getName()])) {
                throw new \LogicException('No logout target path found for current firewall');
            }

            $event->setResponse(new RedirectResponse($this->logoutTargetPathMap[$firewallConfig->getName()]));
        }
    }

    public function storeIdToken(LogoutEvent $event): void
    {
        $idToken = $event->getRequest()->getSession()->get(FranceConnectAuthenticator::ID_TOKEN_SESSION_KEY);

        if (is_string($idToken)) {
            $this->idToken = $idToken;
        }
    }

    public function redirectOnLogout(LogoutEvent $event): void
    {
        if (null === $this->idToken) {
            return;
        }

        $uri = rtrim($this->baseUri, '/').'/logout?'.http_build_query([
                'id_token_hint' => $this->idToken,
                'post_logout_redirect_uri' => $this->urlGenerator->generate(self::LOGOUT_CALLBACK_ROUTE, [], UrlGeneratorInterface::ABSOLUTE_URL),
                'state' => bin2hex(random_bytes(16)),
            ]);

        $event->setResponse(new RedirectResponse($uri));
    }
}
