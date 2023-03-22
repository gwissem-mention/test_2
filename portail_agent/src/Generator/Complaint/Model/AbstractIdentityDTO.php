<?php

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
    protected string $job;
    protected string $country;
    protected string $departement;
    protected string $postalCode;
    protected string $inseeCode;
    protected string $city;
    protected string $departmentNumber;
    //    protected string $familyStatus;
    protected string $streetNumber;
    protected string $streetType;
    protected string $streetName;
    protected string $address;
    protected string $place;
    protected string $birthplace;

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
        $this->birthDepartmentNumber = !is_null($identity->getBirthDepartmentNumber()) ? strval($identity->getBirthDepartmentNumber()) : '';
        //        $this->familyStatus = $identity->getFamilyStatus() ?? '';
        $this->nationality = $identity->getNationality() ?? '';
        $this->job = $identity->getJob() ?? '';
        $this->country = $identity->getAddressCountry() ?? '';
        $this->departement = $identity->getAddressDepartment() ?? '';
        $this->postalCode = $identity->getAddressPostcode() ?? '';
        $this->inseeCode = $identity->getAddressInseeCode() ?? '';
        $this->city = $identity->getAddressCity() ?? '';
        $this->departmentNumber = !is_null($identity->getAddressDepartmentNumber()) ? strval($identity->getAddressDepartmentNumber()) : '';
        $this->streetNumber = $identity->getAddressStreetNumber() ?? '';
        $this->streetType = $identity->getAddressStreetType() ?? '';
        $this->streetName = $identity->getAddressStreetName() ?? '';
        $this->address = $identity->getAddress() ?? '';

        if ($identity->getAddressCity() && $identity->getAddressPostcode() && $identity->getAddressCountry()) {
            $this->place = $identity->getAddressCity().' '.$identity->getAddressPostcode().' ('.$identity->getAddressCountry().')';
        } else {
            $this->place = '';
        }

        if ($identity->getBirthCity() && $identity->getBirthPostalCode() && $identity->getBirthCountry()) {
            $this->birthplace = $identity->getBirthCity().' '.$identity->getBirthPostalCode().' ('.$identity->getBirthCountry().')';
        } else {
            $this->birthplace = '';
        }
    }
}
