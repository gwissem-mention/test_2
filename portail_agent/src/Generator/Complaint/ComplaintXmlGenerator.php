<?php

declare(strict_types=1);

namespace App\Generator\Complaint;

use App\Entity\Complaint;
use App\Entity\FactsObjects\AdministrativeDocument;
use App\Entity\FactsObjects\MultimediaObject;
use App\Entity\FactsObjects\PaymentMethod;
use App\Generator\Complaint\Model\ContactDTO;
use App\Generator\Complaint\Model\FactsDTO;
use App\Generator\Complaint\Model\FlagDTO;
use App\Generator\Complaint\Model\Objects\AdministrativeDocumentDTO;
use App\Generator\Complaint\Model\Objects\MultimediaObjectDTO;
use App\Generator\Complaint\Model\Objects\ObjectsDTO;
use App\Generator\Complaint\Model\Objects\PaymentMethodDTO;
use App\Generator\Complaint\Model\PersonDTO;
use App\Referential\Entity\Unit;

class ComplaintXmlGenerator implements ComplaintGeneratorInterface
{
    public function generate(Complaint $complaint, Unit $unit): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="ISO-8859-1"?><PlainteWeb></PlainteWeb>');

        $xml = $this->arrayToXml($xml, (new FlagDTO($complaint, $unit))->getArray());

        if ($identity = $complaint->getIdentity()) {
            $xml = $this->arrayToXml($xml, (new PersonDTO($identity))->getArray());
        }
        if ($fact = $complaint->getFacts()) {
            $xml = $this->arrayToXml($xml, (new FactsDTO($complaint))->getArray());
        }
        $xml = $this->setObjects($xml, $complaint);
        $xml = $this->arrayToXml($xml, (new ContactDTO($complaint))->getArray());

        return $xml;
    }

    /** @param mixed[] $data */
    private function arrayToXml(\SimpleXMLElement $xml, array $data): \SimpleXMLElement
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xml->addChild(utf8_encode($key));
                $this->arrayToXml($subnode, $value);
            } elseif (is_string($value)) {
                $xml->addChild(utf8_encode($key), utf8_encode($value));
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
                    $objectXml = $this->arrayToXml($objectXml, (new AdministrativeDocumentDTO($object))->getArray());
                    break;
                case $object instanceof MultimediaObject:
                    $objectXml = $this->arrayToXml($objectXml, (new MultimediaObjectDTO($object))->getArray());
                    break;
                case $object instanceof PaymentMethod:
                    $objectXml = $this->arrayToXml($objectXml, (new PaymentMethodDTO($object))->getArray());
                    break;
            }
        }

        $this->arrayToXml($objectXml, (new ObjectsDTO($complaint))->getArray());

        return $xml;
    }
}