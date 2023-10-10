<?php

declare(strict_types=1);

namespace App\Generator\Complaint;

use App\Entity\Complaint;
use App\Entity\FactsObjects\AdministrativeDocument;
use App\Entity\FactsObjects\MultimediaObject;
use App\Entity\FactsObjects\PaymentMethod;
use App\Entity\FactsObjects\SimpleObject;
use App\Entity\FactsObjects\Vehicle;
use App\Entity\Identity;
use App\Generator\Complaint\Model\ContactDTO;
use App\Generator\Complaint\Model\CorporationRepresentedDTO;
use App\Generator\Complaint\Model\FactsDTO;
use App\Generator\Complaint\Model\FlagDTO;
use App\Generator\Complaint\Model\Objects\AdministrativeDocumentDTO;
use App\Generator\Complaint\Model\Objects\MultimediaObjectDTO;
use App\Generator\Complaint\Model\Objects\NonRegisteredVehicleDTO;
use App\Generator\Complaint\Model\Objects\ObjectsDTO;
use App\Generator\Complaint\Model\Objects\PaymentMethodDTO;
use App\Generator\Complaint\Model\Objects\SimpleObjectDTO;
use App\Generator\Complaint\Model\Objects\VehicleDTO;
use App\Generator\Complaint\Model\PersonDTO;
use App\Generator\Complaint\Model\PersonLegalRepresentativeDTO;
use App\Generator\Complaint\Model\VariousDTO;
use App\Referential\Entity\Unit;
use App\Referential\Repository\UnitRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ComplaintXmlGenerator implements ComplaintGeneratorInterface
{
    public function __construct(
        readonly private TranslatorInterface $translator,
        readonly private ComplaintXmlAdditionalInformationPN $additionalInformationPN,
        readonly private UnitRepository $unitRepository,
        readonly private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function generate(Complaint $complaint, Unit $unit): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="ISO-8859-1"?><PlainteWeb></PlainteWeb>');

        $xml = $this->arrayToXml($xml, (new FlagDTO($complaint, $unit))->getArray());

        if (($identity = $complaint->getIdentity()) && Identity::DECLARANT_STATUS_VICTIM === $identity->getDeclarantStatus()) {
            $xml = $this->arrayToXml($xml, (new PersonDTO($identity, $complaint))->getArray());
        } elseif ($identity && Identity::DECLARANT_STATUS_PERSON_LEGAL_REPRESENTATIVE === $identity->getDeclarantStatus() && ($victimIdentity = $complaint->getPersonLegalRepresented())) {
            $xml = $this->arrayToXml($xml, (new PersonDTO($victimIdentity, $complaint))->getArray());
            $xml = $this->arrayToXml($xml, (new PersonLegalRepresentativeDTO($identity))->getArray());
        } elseif ($identity && Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE === $identity->getDeclarantStatus() && ($corporation = $complaint->getCorporationRepresented())) {
            $xml = $this->arrayToXml($xml, (new PersonDTO($identity, $complaint))->getArray());
            $xml = $this->arrayToXml($xml, (new CorporationRepresentedDTO($corporation, $identity))->getArray());
        }
        if ($complaint->getFacts()) {
            $data = (new FactsDTO($complaint))->getArray();
            $data['Faits']['Faits_Expose'] .= $this->additionalInformationPN->set($complaint);
            $data['Faits']['Faits_Prejudice_Physique_Description'] = $data['Faits']['Faits_Prejudice_Physique_Description'] ? $this->translator->trans($data['Faits']['Faits_Prejudice_Physique_Description']) : '';
            $xml = $this->arrayToXml($xml, $data);
        }
        $xml = $this->setObjects($xml, $complaint);
        $xml = $this->arrayToXml($xml, (new ContactDTO($complaint))->getArray());
        $xml = $this->arrayToXml($xml, (new VariousDTO($complaint, $this->unitRepository, $this->urlGenerator))->getArray());

        return $xml;
    }

    /** @param mixed[] $data */
    private function arrayToXml(\SimpleXMLElement $xml, array $data): \SimpleXMLElement
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xml->addChild($key);
                $this->arrayToXml($subnode, $value);
            } elseif (is_string($value)) {
                $xml->addChild($key, $value);
            }
        }

        return $xml;
    }

    private function setObjects(\SimpleXMLElement $xml, Complaint $complaint): \SimpleXMLElement
    {
        $objectXml = $xml->addChild('Objet');

        foreach ($complaint->getObjects() as $object) {
            switch (true) {
                case $object instanceof AdministrativeDocument:
                    $objectXml = $this->arrayToXml($objectXml, (new AdministrativeDocumentDTO($object, $complaint->getIdentity()))->getArray());
                    break;
                case $object instanceof MultimediaObject:
                    $objectXml = $this->arrayToXml($objectXml, (new MultimediaObjectDTO($object))->getArray());
                    break;
                case $object instanceof PaymentMethod:
                    $objectXml = $this->arrayToXml($objectXml, (new PaymentMethodDTO($object))->getArray());
                    break;
                case $object instanceof SimpleObject:
                    $objectXml = $this->arrayToXml($objectXml, (new SimpleObjectDTO($object))->getArray());
                    break;
                case $object instanceof Vehicle && false === $object->isRegistered():
                    $objectXml = $this->arrayToXml($objectXml, (new NonRegisteredVehicleDTO($object))->getArray());
                    break;
                case $object instanceof Vehicle:
                    $objectXml = $this->arrayToXml($objectXml, (new VehicleDTO($object))->getArray());
                    break;
            }
        }

        $this->arrayToXml($objectXml, (new ObjectsDTO($complaint))->getArray());

        return $xml;
    }
}
