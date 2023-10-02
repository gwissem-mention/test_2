<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\Identity;

abstract class AbstractIdentityDTO
{
    protected string $civilStatus;
    protected string $lastname;
    protected string $marriedName;
    protected string $firstname;
    protected string $birthdate;
    protected string $birthCountry;
    protected string $birthDepartment;
    protected string $birthPostalCode;
    protected string $birthInseeCode;
    protected string $birthCity;
    protected string $birthDepartmentNumber;
    protected string $nationality;
    protected string $jobThesaurus;
    protected string $country;
    protected string $departement;
    protected string $postalCode;
    protected string $inseeCode;
    protected string $city;
    protected string $departmentNumber;
    protected string $familySituation;
    protected string $streetNumber;
    protected string $streetType;
    protected string $streetName;
    protected string $address;
    protected string $homePlace;
    protected string $birthplace;

    protected string $mobilePhone;

    public function __construct(Identity $identity)
    {
        $this->civilStatus = Identity::CIVILITY_MALE === $identity->getCivility() ? 'M' : (Identity::CIVILITY_FEMALE === $identity->getCivility() ? 'Mme' : '');
        $this->lastname = $identity->getLastname() ?? '';
        $this->marriedName = $identity->getMarriedName() ?? '';
        $this->firstname = $identity->getFirstname() ?? '';
        $this->birthdate = $identity->getBirthday()?->format('d/m/Y') ?? '';
        $this->birthCountry = $identity->getBirthCountry() ?? '';
        $this->birthDepartment = $identity->getBirthDepartment() ?? '';
        $this->birthPostalCode = $identity->getBirthPostalCode() ?? '';
        $this->birthInseeCode = $identity->getBirthInseeCode() ?? '';
        $this->birthCity = $identity->getBirthCity() ?? '';
        $this->birthDepartmentNumber = null !== $identity->getBirthDepartmentNumber() ? strval($identity->getBirthDepartmentNumber()) : '';
        $this->familySituation = $identity->getFamilySituation() ?? '';
        $this->nationality = $identity->getNationality() ?? '';
        $this->jobThesaurus = $identity->getJobThesaurus() ?? '';
        $this->country = $identity->getAddressCountry() ?? '';
        $this->departement = $identity->getAddressDepartment() ?? '';
        $this->postalCode = $identity->getAddressPostcode() ?? '';
        $this->inseeCode = $identity->getAddressInseeCode() ?? '';
        $this->city = $identity->getAddressCity() ?? '';
        $this->departmentNumber = null !== $identity->getAddressDepartmentNumber() ? strval($identity->getAddressDepartmentNumber()) : '';
        $this->streetNumber = $identity->getAddressStreetNumber() ?? '';
        $this->streetType = $identity->getAddressStreetCompleteName();
        $this->streetName = $identity->getAddressStreetName() ?? '';
        $this->address = $identity->getAddress() ?? '';
        $this->homePlace = ($identity->getAddressCountry() ? strtoupper($identity->getAddressCountry()).', ' : '').($identity->getAddressCity() ? strtoupper($identity->getAddressCity()).', ' : '').($identity->getAddress() ? $identity->getAddress() : '');
        $this->birthplace = ($identity->getBirthCity() ? strtoupper($identity->getBirthCity()).' ' : '').($identity->getBirthPostalCode() ? $identity->getBirthPostalCode().' ' : '').($identity->getBirthCountry() ? '('.$identity->getBirthCountry().')' : '');
        $this->mobilePhone = $identity->getMobilePhone() ?? '';
    }
}
