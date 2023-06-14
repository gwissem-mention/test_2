<?php

declare(strict_types=1);

namespace App\Complaint\DTO;

class ParserAddressDTO
{
    private string $address;
    private string $city;
    private string $postCode;
    private string $inseeCode;
    private string $streetNumber;
    private string $streetType;
    private string $streetName;
    private string $department;
    private int $departmentNumber;

    public function __construct(string $address = '', string $city = '', string $postCode = '', string $inseeCode = '', string $streetNumber = '', string $streetType = '', string $streetName = '', string $department = '', int $departmentNumber = 0)
    {
        $this->address = $address;
        $this->city = $city;
        $this->postCode = $postCode;
        $this->inseeCode = $inseeCode;
        $this->streetNumber = $streetNumber;
        $this->streetType = $streetType;
        $this->streetName = $streetName;
        $this->department = $department;
        $this->departmentNumber = $departmentNumber;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPostCode(): string
    {
        return $this->postCode;
    }

    public function getInseeCode(): string
    {
        return $this->inseeCode;
    }

    public function getStreetNumber(): string
    {
        return $this->streetNumber;
    }

    public function getStreetType(): string
    {
        return $this->streetType;
    }

    public function getStreetName(): string
    {
        return $this->streetName;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function getDepartmentNumber(): int
    {
        return $this->departmentNumber;
    }
}
