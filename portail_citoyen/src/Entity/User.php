<?php

namespace App\Entity;

use App\AppEnum\Gender;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private string $sub;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $familyName;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $givenName;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $preferredUsername;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $birthDate;

    #[ORM\Column(type: Types::STRING, length: 5)]
    private string $birthPlace;

    #[ORM\Column(type: Types::STRING, length: 5)]
    private string $birthCountry;

    #[ORM\Column(type: Types::STRING, length: 6, enumType: Gender::class)]
    private Gender $gender;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $email;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
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

    public function getId(): int
    {
        if (null === $this->id) {
            throw new \LogicException(sprintf('User %s is not persisted', $this->sub));
        }

        return $this->id;
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
