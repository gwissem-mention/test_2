<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

use App\Entity\FactsObjects\AbstractObject;
use App\Entity\FactsObjects\AdministrativeDocument;
use App\Entity\FactsObjects\MultimediaObject;
use App\Entity\FactsObjects\PaymentMethod;
use App\Entity\FactsObjects\SimpleObject;
use App\Entity\FactsObjects\Vehicle;

class ObjectsParser
{
    private const DOCUMENT = 1;
    private const PAYMENT_METHOD = 2;
    private const MULTIMEDIA = 3;
    private const REGISTERED_VEHICLE = 4;
    private const UNREGISTERED_VEHICLE = 5;

    private string $complaintFrontId;

    public function __construct(
        private readonly PhoneParser $phoneParser,
        private readonly FileParser $fileParser,
        private readonly DateParser $dateParser,
        private readonly AddressParser $addressParser,
    ) {
    }

    public function setComplaintFrontId(string $complaintFrontId): void
    {
        $this->complaintFrontId = $complaintFrontId;
    }

    /**
     * @param array<object> $objectsInput
     *
     * @return array<AbstractObject>
     */
    public function parseAll(array $objectsInput): array
    {
        $objects = [];
        foreach ($objectsInput as $objectInput) {
            $this->parse($objectInput);
        }

        return $objects;
    }

    public function parse(object $objectInput): AbstractObject
    {
        $object = match ($objectInput->category->code) {
            self::DOCUMENT => $this->parseAdministrativeDocument($objectInput),
            self::PAYMENT_METHOD => $this->parsePaymentMethod($objectInput),
            self::MULTIMEDIA => $this->parseMultimediaObject($objectInput),
            self::REGISTERED_VEHICLE, self::UNREGISTERED_VEHICLE => $this->parseVehicle($objectInput),
            default => $this->parseSimpleObject($objectInput),
        };

        $this->parseObjectBasic($object, $objectInput);
        $this->parseFiles($object, $objectInput);

        return $object;
    }

    public function parseAdministrativeDocument(object $objectInput): AdministrativeDocument
    {
        $documentObject = new AdministrativeDocument();

        $documentObject
            ->setType($objectInput->documentType ?? '')
            ->setOwned($objectInput->documentOwned)
            ->setOwnerLastname($objectInput->documentAdditionalInformation?->documentOwnerLastName)
            ->setOwnerFirstname($objectInput->documentAdditionalInformation?->documentOwnerFirstName)
            ->setOwnerCompany($objectInput->documentAdditionalInformation?->documentOwnerCompany)
            ->setOwnerEmail($objectInput->documentAdditionalInformation?->documentOwnerEmail)
            ->setNumber($objectInput->documentAdditionalInformation?->documentNumber)
            ->setIssuedBy($objectInput->documentAdditionalInformation?->documentIssuedBy);

        if ($objectInput->documentAdditionalInformation?->documentOwnerPhone) {
            $documentObject->setOwnerPhone($this->phoneParser->parse($objectInput->documentAdditionalInformation?->documentOwnerPhone));
        }

        if ($objectInput->documentAdditionalInformation?->documentIssuedOn) {
            $documentObject->setIssuedOn($this->dateParser->parse($objectInput->documentAdditionalInformation?->documentIssuedOn));
        }

        if ($objectInput->documentAdditionalInformation?->documentValidityEndDate) {
            $documentObject->setValidityEndDate($this->dateParser->parse($objectInput->documentAdditionalInformation?->documentValidityEndDate));
        }

        if ($objectInput->documentAdditionalInformation?->documentOwnerAddress) {
            $this->parseAddress($documentObject, $objectInput->documentAdditionalInformation?->documentOwnerAddress);
        }

        return $documentObject;
    }

    public function parseMultimediaObject(object $objectInput): MultimediaObject
    {
        $multimediaObject = new MultimediaObject();

        $multimediaObject
            ->setLabel($objectInput->label)
            ->setNature($objectInput->category->label)
            ->setBrand($objectInput->brand)
            ->setModel($objectInput->model)
            ->setOperator($objectInput->operator)
            ->setSerialNumber($objectInput->serialNumber)
            ->setStillOnWhenMobileStolen($objectInput->stillOnWhenMobileStolen)
            ->setPinEnabledWhenMobileStolen($objectInput->pinEnabledWhenMobileStolen)
            ->setMobileInsured($objectInput->mobileInsured)
            ->setKeyboardLockedWhenMobileStolen($objectInput->keyboardLockedWhenMobileStolen);

        if ($objectInput->phoneNumberLine) {
            $multimediaObject->setPhoneNumber($this->phoneParser->parse($objectInput->phoneNumberLine));
        }

        return $multimediaObject;
    }

    private function parseObjectBasic(AbstractObject $abstractObject, object $objectInput): void
    {
        $abstractObject
            ->setAmount($objectInput->amount)
            ->setStatus(AbstractObject::STATUS_STOLEN === $objectInput->status->code ? AbstractObject::STATUS_STOLEN : AbstractObject::STATUS_DEGRADED);
    }

    private function parsePaymentMethod(object $objectInput): PaymentMethod
    {
        $paymentMethod = new PaymentMethod();

        $paymentMethod
            ->setDescription('') // TODO check whey this is required
            ->setType($objectInput->label)
            ->setBank($objectInput->bank);

        return $paymentMethod;
    }

    private function parseVehicle(object $objectInput): Vehicle
    {
        $vehicle = new Vehicle();
        $vehicle->setLabel($objectInput->label);
        $vehicle->setBrand($objectInput->brand ?? '');

        $vehicle->setModel($objectInput->model);
        $vehicle->setRegistrationNumber($objectInput->registrationNumber);
        $vehicle->setRegistrationCountry($objectInput->registrationNumberCountry);
        $vehicle->setInsuranceCompany($objectInput->insuranceCompany);
        $vehicle->setInsuranceNumber($objectInput->insuranceNumber);

        return $vehicle;
    }

    private function parseSimpleObject(object $objectInput): SimpleObject
    {
        $simpleObject = new SimpleObject();

        $simpleObject->setNature($objectInput->label);
        $simpleObject->setDescription($objectInput->description);

        return $simpleObject;
    }

    private function parseFiles(AbstractObject $object, object $objectInput): void
    {
        foreach ($objectInput->files as $fileInput) {
            $object->addFile($this->fileParser->parse($fileInput, $this->complaintFrontId));
        }
    }

    private function parseAddress(AdministrativeDocument $object, object $addressInput): void
    {
        $address = $this->addressParser->parseFrenchAddress($addressInput);

        $object
            ->setOwnerAddress($address->getAddress())
            ->setOwnerAddressStreetType($address->getStreetType())
            ->setOwnerAddressStreetNumber($address->getStreetNumber())
            ->setOwnerAddressStreetName($address->getStreetName())
            ->setOwnerAddressInseeCode($address->getInseeCode())
            ->setOwnerAddressPostCode($address->getPostcode())
            ->setOwnerAddressCity($address->getCity())
            ->setOwnerAddressDepartment($address->getDepartment())
            ->setOwnerAddressDepartmentNumber((string) $address->getDepartmentNumber());
    }
}
