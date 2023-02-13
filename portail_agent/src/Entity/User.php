<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Institution;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

#[UniqueEntity(fields: ['number', 'institution'])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(
    columns: ['number', 'institution']
)]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 510, unique: true)]
    private string $identifier;

    #[ORM\Column(length: 255)]
    private string $number;

    /**
     * @var array<string>
     */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $appellation = null;

    #[ORM\Column(length: 255, enumType: Institution::class)]
    private Institution $institution;

    #[ORM\Column(length: 255)]
    private ?string $serviceCode = null;

    #[ORM\Column(length: 255, options: ['default' => 'Europe/Paris'])]
    private ?string $timezone = 'Europe/Paris';

    /** @var Collection<int, Notification> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notification::class, cascade: [
        'persist',
        'remove',
    ], orphanRemoval: true)]
    private Collection $notifications;

    public function __construct(string $number, Institution $institution, bool $supervisor = false)
    {
        $this->number = $number;
        $this->institution = $institution;
        $this->identifier = self::generateIdentifier($number, $institution);
        $this->notifications = new ArrayCollection();

        if (true === $supervisor) {
            $this->addRole('ROLE_SUPERVISOR');
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function getUserIdentifier(): string
    {
        return $this->identifier;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getAppellation(): ?string
    {
        return $this->appellation;
    }

    public function setAppellation(?string $appellation): self
    {
        $this->appellation = $appellation;

        return $this;
    }

    public function getInstitution(): Institution
    {
        return $this->institution;
    }

    public function getServiceCode(): ?string
    {
        return $this->serviceCode;
    }

    public function setServiceCode(?string $serviceCode): self
    {
        $this->serviceCode = $serviceCode;

        return $this;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): User
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public static function generateIdentifier(string $number, string|Institution $institution): string
    {
        if ($institution instanceof Institution) {
            $institution = $institution->name;
        }

        return sprintf('%s-%s', $number, $institution);
    }

    public function getInitials(): string
    {
        $words = explode(' ', (string) $this->appellation);

        return mb_strtoupper(
            mb_substr($words[0], 0, 1, 'UTF-8').
            mb_substr(end($words), 0, 1, 'UTF-8'),
            'UTF-8'
        );
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function isSupervisor(): bool
    {
        return in_array('ROLE_SUPERVISOR', $this->roles, true);
    }
}
