<?php

declare(strict_types=1);

namespace App\Security;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class FranceConnectAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{
    public const LOGIN_CALLBACK_ROUTE = 'france_connect_callback';
    public const ID_TOKEN_SESSION_KEY = 'fc_id_token';

    public function __construct(
        private readonly OAuth2Client $franceConnectClient,
        private readonly LoggerInterface $securityLogger,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        return $this->franceConnectClient->redirect(options: [
            // Nonce is required by FranceConnect but not controlled by the application
            // Since our flow is authorization code and the JWT is not used
            'nonce' => bin2hex(random_bytes(16)),
            'approval_prompt' => null,
        ]);
    }

    public function supports(Request $request): bool
    {
        return self::LOGIN_CALLBACK_ROUTE === $request->attributes->get('_route');
    }

    public function authenticate(Request $request): Passport
    {
        $accessToken = $this->franceConnectClient->getAccessToken();

        if (!$accessToken instanceof AccessToken) {
            throw new \LogicException(sprintf('Recived value of type %s; instance of %s expected', get_debug_type($accessToken), AccessToken::class));
        }

        $rawValues = $accessToken->getValues();
        if (!isset($rawValues['id_token'])) {
            $encodedValues = json_encode($rawValues) ?: '';
            throw new \LogicException(sprintf('id_token missing in %s', $encodedValues));
        }

        $request->getSession()->set(self::ID_TOKEN_SESSION_KEY, $rawValues['id_token']);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken): User {
                $resourceOwner = $this->franceConnectClient->fetchUserFromToken($accessToken);

                if (!is_string($sub = $resourceOwner->getId())) {
                    throw new \RuntimeException(sprintf('User identifier %s is not a string', get_debug_type($sub)));
                }

                $userData = $resourceOwner->toArray();

                return new User(
                    $sub,
                    $userData['family_name'] ?? '',
                    $userData['given_name'] ?? '',
                    $userData['preferred_username'] ?? '',
                    $userData['birthdate'] ?? '',
                    $userData['birthplace'] ?? '',
                    $userData['birthcountry'] ?? '',
                    $userData['gender'] ?? '',
                    $userData['email'] ?? '',
                );
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        $parameters = [];
        /** @var string $targetPath */
        $targetPath = $request->getSession()->get('_security.main.target_path');
        parse_str($targetPath, $parameters);

        return new RedirectResponse($this->urlGenerator->generate('authentication', ['france_connected' => 1, 'my_complaints_reports' => $parameters['my_complaints_reports'] ?? '0', 'identity' => $parameters['identity'] ?? '0']));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $this->securityLogger->warning(
            sprintf(
                'Authentication failure: %s',
                $exception->getMessage(),
            )
        );

        return new RedirectResponse($this->urlGenerator->generate('france_connect_error'));
    }
}
