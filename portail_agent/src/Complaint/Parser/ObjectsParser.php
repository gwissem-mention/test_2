<?php

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
            ->setIssuedBy($objectInput->documentAdditionalInformation?->documentIssuedBy)
        ;

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

        $multimediaObject->setLabel($objectInput->label);

        $multimediaObject->setBrand($objectInput->brand);
        $multimediaObject->setModel($objectInput->model);
        $multimediaObject->setOperator($objectInput->operator);
        $multimediaObject->setSerialNumber($objectInput->serialNumber);

        if ($objectInput->phoneNumberLine) {
            $multimediaObject->setPhoneNumber($this->phoneParser->parse($objectInput->phoneNumberLine));
        }

        return $multimediaObject;
    }

    private function parseObjectBasic(AbstractObject $abstractObject, object $objectInput): void
    {
        $abstractObject->setAmount($objectInput->amount);
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

    private function parseAddress(AbstractObject $object, object $addressInput): void
    {
        match ($addressInput->addressType) {
            'etalab_address' => $this->parseEtalabAddress($object, $addressInput),
            default => $this->parseStandardAddress($object, $addressInput),
        };
    }

    private function parseEtalabAddress(AbstractObject $object, object $addressInput): void
    {
        if ($object instanceof AdministrativeDocument) {
            $object
                ->setOwnerAddress($addressInput->label)
                ->setOwnerAddressStreetType(null) // TODO: extract street type from street name for french address if possible
                ->setOwnerAddressStreetNumber($addressInput->houseNumber)
                ->setOwnerAddressStreetName($addressInput->street)
                ->setOwnerAddressInseeCode($addressInput->citycode)
                ->setOwnerAddressPostcode($addressInput->postcode)
                ->setOwnerAddressCity($addressInput->city);

            $context = array_map('trim', explode(',', $addressInput->context));
            $object
                ->setOwnerAddressDepartmentNumber($context[0])
                ->setOwnerAddressDepartment($context[1]);
        }
    }

    private function parseStandardAddress(AbstractObject $object, object $addressInput): void
    {
        if ($object instanceof AdministrativeDocument) {
            $object
                ->setOwnerAddress($addressInput->label)
                ->setOwnerAddressStreetType(null)
                ->setOwnerAddressStreetNumber(null)
                ->setOwnerAddressStreetName(null)
                ->setOwnerAddressInseeCode(null)
                ->setOwnerAddressPostcode(null)
                ->setOwnerAddressCity(null)
                ->setOwnerAddressDepartment(null);
        }
    }
}
