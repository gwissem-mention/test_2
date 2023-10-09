<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\AppEnum\Institution;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\AgentAuthenticator;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Behat\Hook\AfterScenario;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UserContext extends RawMinkContext
{
    /** @var array<string, string|null> */
    private array $headers = [];

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly bool $ssoIsEnabled,
    ) {
    }

    /**
     * @AfterScenario
     */
    public function resetHeaders(AfterScenarioScope $afterScenario): void
    {
        $this->headers = [];
    }

    /**
     * @Then the "user" :number from :institution exists with:
     */
    public function userWithMatriculeAndInstitutionExistsWithTheFollowingAttributes(
        string $number,
        Institution $institution,
        TableNode $tableNode
    ): void {
        if (null === $user = $this->userRepository->findOneByIdentifier(
            User::generateIdentifier($number, $institution)
        )) {
            throw new \Exception(sprintf('"User" with number="%s" and institution "%s" not found', $number, $institution->name));
        }

        $attributesNormalizers = [
            'code_service' => 'codeService',
        ];

        $valueNormalizers = [
            'roles' => fn (string $value): array => array_map('trim', explode(',', $value)),
        ];

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($tableNode->getColumnsHash() as $hash) {
            $property = $attributesNormalizers[$hash['attribute']] ?? $hash['attribute'];
            $value = array_key_exists($hash['attribute'], $valueNormalizers) ? $valueNormalizers[$hash['attribute']](
                $hash['value']
            ) : $hash['value'];

            if ($value !== $propertyAccessor->getValue($user, $property)) {
                throw new \Exception(sprintf('Value of property "%s" does not match', $hash['attribute']));
            }
        }
    }

    /**
     * @Given I am authenticated with :matricule from :institution
     */
    public function iAmAuthenticatedWith(string $number, Institution $institution): void
    {
        if (null === $user = $this->userRepository->findOneByIdentifier(
            User::generateIdentifier($number, $institution)
        )) {
            throw new \Exception(sprintf('Utilisateur not found with matricule "%s" form institution "%s"', $number, $institution->name));
        }

        $this->headers = [
            AgentAuthenticator::HEADER_NUMBER => $user->getNumber(),
            AgentAuthenticator::HEADER_INSTITUTION => $user->getInstitution()->name,
            AgentAuthenticator::HEADER_APPELLATION => $user->getAppellation(),
            AgentAuthenticator::HEADER_SERVICE_CODE => $user->getServiceCode(),
            AgentAuthenticator::HEADER_PROFILE => $user->isSupervisor() ? 'superviseur' : 'fsi',
        ];

        if ($this->ssoIsEnabled) {
            return;
        }
        $this->getSession()->visit($this->locatePath('/sso-less/start-session'));
        $this->getSession()->getPage()->selectFieldOption(
            'form_user',
            (string) $user->getAppellation()
        );
        $this->getSession()->getPage()->pressButton('Connexion');
    }

    /**
     * @return array<string, string|null>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @Transform :institution
     */
    public function castStringToInstitution(string $institution): Institution
    {
        return Institution::from($institution);
    }
}
