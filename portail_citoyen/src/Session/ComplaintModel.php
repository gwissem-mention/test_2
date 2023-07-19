<?php

declare(strict_types=1);

namespace App\Session;

use App\Form\Model\AdditionalInformation\AdditionalInformationModel;
use App\Form\Model\AppointmentModel;
use App\Form\Model\Facts\FactsModel;
use App\Form\Model\Identity\IdentityModel;
use App\Form\Model\Objects\ObjectsModel;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Uid\Uuid;

class ComplaintModel
{
    private Uuid $id;
    private \DateTimeInterface $createdAt;
    private ?IdentityModel $identity = null;
    private ?FactsModel $facts = null;
    private ?AdditionalInformationModel $additionalInformation = null;
    private ?ObjectsModel $objects = null;
    private ?AppointmentModel $appointment = null;
    private bool $franceConnected = false;
    private ?string $affectedService = null;
    private bool $appointmentRequired = false;

    public function __construct(Uuid $id)
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->id = $id;
    }

    public function getIdentity(): ?IdentityModel
    {
        return $this->identity;
    }

    public function setIdentity(?IdentityModel $identity): self
    {
        $this->identity = $identity;

        return $this;
    }

    public function getFacts(): ?FactsModel
    {
        return $this->facts;
    }

    public function setFacts(?FactsModel $facts): self
    {
        $this->facts = $facts;

        return $this;
    }

    public function getObjects(): ?ObjectsModel
    {
        return $this->objects;
    }

    public function setObjects(?ObjectsModel $objects): self
    {
        $this->objects = $objects;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function isFranceConnected(): bool
    {
        return $this->franceConnected;
    }

    public function setFranceConnected(bool $franceConnected): self
    {
        $this->franceConnected = $franceConnected;

        return $this;
    }

    public function getAdditionalInformation(): ?AdditionalInformationModel
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(?AdditionalInformationModel $additionalInformation): self
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }

    public function getAffectedService(): ?string
    {
        return $this->affectedService;
    }

    public function setAffectedService(?string $affectedService): self
    {
        $this->affectedService = $affectedService;

        return $this;
    }

    public function getAppointment(): ?AppointmentModel
    {
        return $this->appointment;
    }

    public function setAppointment(?AppointmentModel $appointment): self
    {
        $this->appointment = $appointment;

        return $this;
    }

    #[Ignore]
    public function isComplaintIdentityFilled(): bool
    {
        $identity = $this->getIdentity();

        return $identity instanceof IdentityModel && !is_null($identity->getCivilState()->getJob());
    }

    #[Ignore]
    public function isComplaintFactsFilled(): bool
    {
        $facts = $this->getFacts();

        return $facts instanceof FactsModel && !is_null($facts->getDescription());
    }

    public function isAppointmentRequired(): bool
    {
        return $this->appointmentRequired;
    }

    public function setAppointmentRequired(bool $appointmentRequired): self
    {
        $this->appointmentRequired = $appointmentRequired;

        return $this;
    }
}
