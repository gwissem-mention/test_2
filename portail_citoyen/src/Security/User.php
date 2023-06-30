<?php

namespace App\Security;

use App\AppEnum\Gender;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private string $sub;
    private string $familyName;
    private string $givenName;
    private string $preferredUsername;
    private \DateTimeImmutable $birthDate;
    private string $birthPlace;
    private string $birthCountry;
    private Gender $gender;
    private string $email;

    /**
     * @var string[]
     */
    private array $roles = [];

    public function __construct(
        string $sub,
        string $familyName,
        string $givenName,
        string $preferredUsername,
        string $birthDate,
        string $birthPlace,
        string $birthCountry,
        string $gender,
        string $email,
    ) {
        if (0 === strlen($sub)) {
            throw new \RuntimeException('sub can\'t be empty');
        }

        if (0 === strlen($familyName)) {
            throw new \RuntimeException('familyName can\'t be empty');
        }

        if (0 === strlen($givenName)) {
            throw new \RuntimeException('givenName can\'t be empty');
        }

        if (0 === strlen($birthDate)) {
            throw new \RuntimeException('birthDate can\'t be empty');
        }

        if (0 === strlen($birthPlace)) {
            throw new \RuntimeException('birthPlace can\'t be empty');
        }

        if (0 === strlen($birthCountry)) {
            throw new \RuntimeException('birthCountry can\'t be empty');
        }

        if (0 === strlen($gender)) {
            throw new \RuntimeException('gender can\'t be empty');
        }

        if (0 === strlen($email)) {
            throw new \RuntimeException('email can\'t be empty');
        }

        $this->sub = $sub;
        $this->familyName = $familyName;
        $this->givenName = $givenName;
        $this->preferredUsername = $preferredUsername;
        $this->birthDate = new \DateTimeImmutable($birthDate);
        $this->birthPlace = $birthPlace;
        $this->birthCountry = $birthCountry;
        $this->gender = Gender::from($gender);
        $this->email = $email;
    }

    public function getUserIdentifier(): string
    {
        return $this->sub;
    }

    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    public function getGivenName(): string
    {
        return $this->givenName;
    }

    public function getPreferredUsername(): string
    {
        return $this->preferredUsername;
    }

    public function getFullName(): string
    {
        return sprintf('%s %s', $this->getGivenName(), $this->getFamilyName());
    }

    public function getBirthDate(): \DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function getBirthPlace(): string
    {
        return $this->birthPlace;
    }

    public function getBirthCountry(): string
    {
        return $this->birthCountry;
    }

    public function getGender(): Gender
    {
        return $this->gender;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
    }
}
