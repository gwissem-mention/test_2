<?php

declare(strict_types=1);

namespace App\Security;

use App\AppEnum\Institution;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

final class AgentAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    public const HEADER_APPELLATION = 'appelation';
    public const HEADER_NUMBER = 'matricule';
    public const HEADER_INSTITUTION = 'institution';
    public const HEADER_SERVICE_CODE = 'codeservice';
    public const HEADER_SUPERVISOR = 'superviseur';

    public const HEADERS = [
        self::HEADER_APPELLATION,
        self::HEADER_NUMBER,
        self::HEADER_INSTITUTION,
        self::HEADER_SERVICE_CODE,
        self::HEADER_SUPERVISOR,
    ];

    public function __construct(
        private readonly bool $ssoIsEnabled,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UserRepository $userRepository
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has(self::HEADER_NUMBER) && $request->headers->has(self::HEADER_INSTITUTION);
    }

    public function authenticate(Request $request): Passport
    {
        foreach (self::HEADERS as $header) {
            if (false === $request->headers->has($header)) {
                throw new CustomUserMessageAuthenticationException(sprintf('Missing header "%s"', $header));
            }

            $value = trim((string) $request->headers->get($header, ''));

            if (0 === strlen($value)) {
                throw new CustomUserMessageAuthenticationException(sprintf('Header "%s" cannot be empty', $header));
            }
        }

        try {
            $institution = Institution::from((string) $request->headers->get(self::HEADER_INSTITUTION));
        } catch (\ValueError) {
            throw new CustomUserMessageAuthenticationException(sprintf('Institution "%s" is unknown', $request->headers->get(self::HEADER_INSTITUTION)));
        }

        $number = (string) $request->headers->get(self::HEADER_NUMBER);
        $identifier = User::generateIdentifier($number, $institution);
        $appellation = (string) $request->headers->get(self::HEADER_APPELLATION);
        $serviceCode = (string) $request->headers->get(self::HEADER_SERVICE_CODE);
        $supervisor = (bool) $request->headers->get(self::HEADER_SUPERVISOR);

        return new SelfValidatingPassport(
            new UserBadge(
                $identifier,
                function (string $identifier) use ($number, $institution, $appellation, $serviceCode, $supervisor) {
                    return $this->createOrUpdateUser(
                        $identifier,
                        $number,
                        $institution,
                        $appellation,
                        $serviceCode,
                        $supervisor
                    );
                }
            )
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw new AccessDeniedException();
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        if (false === $this->ssoIsEnabled) {
            return new RedirectResponse($this->urlGenerator->generate(SSOLessInterface::START_SESSION_ROUTE));
        }

        return new Response(null, 401);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function createOrUpdateUser(
        string $identifier,
        string $number,
        Institution $institution,
        string $appellation,
        string $serviceCode,
        bool $supervisor
    ): User {
        if (null === $user = $this->userRepository->findOneByIdentifier($identifier)) {
            $user = new User($number, $institution, $supervisor);
        }

        $user
            ->setAppellation($appellation)
            ->setServiceCode($serviceCode);

        if (true === $supervisor) {
            $user->addRole('ROLE_SUPERVISOR');
        }

        if (null !== $rightDelegation = $user->getDelegationGained()) {
            if ($rightDelegation->getEndDate() <= new \DateTimeImmutable()) {
                $user->removeRole('ROLE_DELEGATED');
            } elseif ($rightDelegation->getStartDate() <= new \DateTimeImmutable()) {
                $user->addRole('ROLE_DELEGATED');
            }
        }
        $this->userRepository->save($user, true);

        return $user;
    }
}
