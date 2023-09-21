<?php

declare(strict_types=1);

namespace App\Complaint\Parser;

use App\Complaint\ComplaintFileParser;
use App\Entity\FactsObjects\AbstractObject;
use App\Entity\FactsObjects\AdministrativeDocument;
use App\Entity\FactsObjects\MultimediaObject;
use App\Entity\FactsObjects\PaymentMethod;
use App\Entity\FactsObjects\SimpleObject;
use App\Entity\FactsObjects\Vehicle;
use League\Flysystem\FilesystemException;

/**
 * @phpstan-import-type JsonDate from DateParser
 * @phpstan-import-type JsonPhone from PhoneParser
 * @phpstan-import-type JsonFile from FileParser
 * @phpstan-import-type JsonFrenchAddress from AddressParser
 *
 * @phpstan-type JsonObject object{
 *      category: object{code: int, label: string},
 *      amount: float|null,
 *      status: object{code: int, label: string},
 *      documentType: string|null,
 *      documentOwned: bool,
 *      documentAdditionalInformation: object{
 *           documentOwnerLastName: string|null,
 *           documentOwnerFirstName: string|null,
 *           documentOwnerCompany: string|null,
 *           documentOwnerEmail: string|null,
 *           documentOwnerPhone: JsonPhone|null,
 *           documentOwnerAddress: JsonFrenchAddress|null,
 *      }|null,
 *       documentNumber: string|null,
 *       documentIssuedBy: string|null,
 *       documentIssuedOn: JsonDate|null,
 *       documentValidityEndDate: JsonDate|null,
 *       description: string|null,
 *       documentIssuingCountry: object{label: string}|null,
 *      brand: string,
 *      model: string|null,
 *      serialNumber: string|null,
 *      description: string|null,
 *      multimediaNature: string|null,
 *      operator: string|null,
 *      stillOnWhenMobileStolen: bool|null,
 *      pinEnabledWhenMobileStolen: bool|null,
 *      mobileInsured: bool|null,
 *      keyboardLockedWhenMobileStolen: bool|null,
 *      ownerLastname: string|null,
 *      ownerFirstname: string|null,
 *      phoneNumberLine: JsonPhone|null,
 *      registrationNumber: string|null,
 *      registrationNumberCountry: string|null,
 *      insuranceCompany: string|null,
 *      insuranceNumber: string|null,
 *      registeredVehicleNature: string|null,
 *      degradationDescription: string|null,
 *      label: string,
 *      paymentCategory: string,
 *      bank: string|null,
 *      bankAccountNumber: string|null,
 *      checkNumber: string|null,
 *      checkFirstNumber: string|null,
 *      checkLastNumber: string|null,
 *      files: array<JsonFile>,
 *      creditCardNumber: string|null,
 *  }
 *
 * @phpstan-import-type JsonComplaint from ComplaintFileParser
 */
class ObjectsParser
{
    private const DOCUMENT = 1;
    private const PAYMENT_METHOD = 2;
    private const MOBILE_PHONE = 3;
    private const REGISTERED_VEHICLE = 4;
    private const UNREGISTERED_VEHICLE = 5;
    private const MULTIMEDIA = 7;

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
     * @param array<JsonObject> $objectsInput
     * @param JsonComplaint     $complaintJson
     *
     * @return array<AbstractObject>
     */
    public function parseAll(array $objectsInput, object $complaintJson): array
    {
        $objects = [];
        foreach ($objectsInput as $objectInput) {
            $this->parse($objectInput, $complaintJson);
        }

        return $objects;
    }

    /**
     * @param JsonObject    $objectInput
     * @param JsonComplaint $complaintJson
     *
     * @throws FilesystemException
     */
    public function parse(object $objectInput, object $complaintJson): AbstractObject
    {
        $object = match ($objectInput->category->code) {
            self::DOCUMENT => $this->parseAdministrativeDocument($objectInput),
            self::PAYMENT_METHOD => $this->parsePaymentMethod($objectInput),
            self::MOBILE_PHONE, self::MULTIMEDIA => $this->parseMultimediaObject($objectInput, $complaintJson),
            self::REGISTERED_VEHICLE, self::UNREGISTERED_VEHICLE => $this->parseVehicle($objectInput),
            default => $this->parseSimpleObject($objectInput),
        };

        $this->parseObjectBasic($object, $objectInput);
        $this->parseFiles($object, $objectInput);

        return $object;
    }

    /**
     * @param JsonObject $objectInput
     *
     * @throws \Exception
     */
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
            ->setNumber($objectInput->documentNumber)
            ->setIssuedBy($objectInput->documentIssuedBy)
            ->setDescription($objectInput->description)
            ->setIssuingCountry($objectInput->documentIssuingCountry?->label);

        if ($objectInput->documentAdditionalInformation?->documentOwnerPhone) {
            $documentObject->setOwnerPhone($this->phoneParser->parse($objectInput->documentAdditionalInformation->documentOwnerPhone));
        }

        if ($objectInput->documentIssuedOn) {
            $documentObject->setIssuedOn($this->dateParser->parse($objectInput->documentIssuedOn));
        }

        if ($objectInput->documentValidityEndDate) {
            $documentObject->setValidityEndDate($this->dateParser->parse($objectInput->documentValidityEndDate));
        }

        if ($objectInput->documentAdditionalInformation?->documentOwnerAddress) {
            $this->parseAddress($documentObject, $objectInput->documentAdditionalInformation->documentOwnerAddress);
        }

        return $documentObject;
    }

    /**
     * @param JsonObject    $objectInput
     * @param JsonComplaint $complaintJson
     */
    public function parseMultimediaObject(object $objectInput, object $complaintJson): MultimediaObject
    {
        $multimediaObject = new MultimediaObject();

        $multimediaObject
            ->setBrand($objectInput->brand)
            ->setModel($objectInput->model)
            ->setSerialNumber($objectInput->serialNumber)
            ->setDescription($objectInput->description);

        if (self::MOBILE_PHONE === $objectInput->category->code) {
            $multimediaObject
                ->setNature(strtoupper(str_replace('é', 'e', $objectInput->category->label)))
                ->setOperator($objectInput->operator)
                ->setStillOnWhenMobileStolen($objectInput->stillOnWhenMobileStolen)
                ->setPinEnabledWhenMobileStolen($objectInput->pinEnabledWhenMobileStolen)
                ->setMobileInsured($objectInput->mobileInsured)
                ->setKeyboardLockedWhenMobileStolen($objectInput->keyboardLockedWhenMobileStolen);
        }

        if (self::MULTIMEDIA === $objectInput->category->code) {
            $multimediaObject->setNature($objectInput->multimediaNature ?? '');
            if ($objectInput->ownerLastname != $complaintJson->identity->civilState->birthName || $objectInput->ownerFirstname != $complaintJson->identity->civilState->firstnames) {
                $multimediaObject
                    ->setOwned(false)
                    ->setOwnerLastname($objectInput->ownerLastname)
                    ->setOwnerFirstname($objectInput->ownerFirstname);
            }
        }

        if ($objectInput->phoneNumberLine) {
            $multimediaObject->setPhoneNumber($this->phoneParser->parse($objectInput->phoneNumberLine));
        }

        return $multimediaObject;
    }

    /**
     * @param JsonObject $objectInput
     */
    private function parseObjectBasic(AbstractObject $abstractObject, object $objectInput): void
    {
        $abstractObject
            ->setAmount($objectInput->amount)
            ->setStatus(AbstractObject::STATUS_STOLEN === $objectInput->status->code ? AbstractObject::STATUS_STOLEN : AbstractObject::STATUS_DEGRADED);
    }

    /**
     * @param JsonObject $objectInput
     */
    private function parsePaymentMethod(object $objectInput): PaymentMethod
    {
        $paymentMethod = new PaymentMethod();

        return $paymentMethod
            ->setDescription($objectInput->label)
            ->setType($objectInput->paymentCategory)
            ->setBank($objectInput->bank)
            ->setBankAccountNumber($objectInput->bankAccountNumber)
            ->setChequeNumber($objectInput->checkNumber)
            ->setFirstChequeNumber($objectInput->checkFirstNumber)
            ->setLastChequeNumber($objectInput->checkLastNumber)
            ->setCreditCardNumber($objectInput->creditCardNumber);
    }

    /**
     * @param JsonObject $objectInput
     */
    private function parseVehicle(object $objectInput): Vehicle
    {
        $vehicle = new Vehicle();

        return $vehicle
            ->setBrand($objectInput->brand ?? '')
            ->setModel($objectInput->model)
            ->setRegistrationNumber($objectInput->registrationNumber)
            ->setRegistrationCountry($objectInput->registrationNumberCountry)
            ->setInsuranceCompany($objectInput->insuranceCompany)
            ->setInsuranceNumber($objectInput->insuranceNumber)
            ->setNature($objectInput->registeredVehicleNature)
            ->setDegradationDescription($objectInput->degradationDescription);
    }

    /**
     * @param JsonObject $objectInput
     */
    private function parseSimpleObject(object $objectInput): SimpleObject
    {
        $simpleObject = new SimpleObject();

        return $simpleObject
            ->setNature($objectInput->label)
            ->setDescription($objectInput->description ?? '')
            ->setSerialNumber($objectInput->serialNumber);
    }

    /**
     * @param JsonObject $objectInput
     *
     * @throws \League\Flysystem\FilesystemException
     */
    private function parseFiles(AbstractObject $object, object $objectInput): void
    {
        foreach ($objectInput->files as $fileInput) {
            $object->addFile($this->fileParser->parse($fileInput, $this->complaintFrontId));
        }
    }

    /**
     * @param JsonFrenchAddress $addressInput
     */
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
