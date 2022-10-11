<?php

declare(strict_types=1);

namespace App\FranceConnect;

class Identity
{
    public function __construct(
        private readonly string $givenName,
        private readonly string $familyName,
        private readonly string $birthDate,
        private readonly string $gender,
        private readonly string $birthPlace,
        private readonly string $birthCountry,
        private readonly string $email
    ) {
    }

    public function getGivenName(): string
    {
        return $this->givenName;
    }

    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getBirthPlace(): string
    {
        return $this->birthPlace;
    }

    public function getBirthCountry(): string
    {
        return $this->birthCountry;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
