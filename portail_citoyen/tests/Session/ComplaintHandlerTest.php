<?php

namespace App\Tests\Session;

use App\Form\Model\Address\AddressEtalabModel;
use App\Form\Model\Facts\FactsModel;
use App\Form\Model\Identity\IdentityModel;
use App\Session\ComplaintHandler;
use App\Session\ComplaintModel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class ComplaintHandlerTest extends KernelTestCase
{
    private readonly ComplaintHandler $complaintHandler;

    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var ComplaintHandler $complaintHandler */
        $complaintHandler = $container->get(ComplaintHandler::class);

        $this->complaintHandler = $complaintHandler;
    }

    public function testGetAffectedService(): void
    {
        $complaint = new ComplaintModel(Uuid::v1());
        $identity = new IdentityModel();
        $facts = new FactsModel();

        $identity->getContactInformation()->setFrenchAddress(
            (new AddressEtalabModel())
                ->setId('75111_8158')
                ->setLabel('Avenue de la République 75011 Paris')
                ->setType('street')
                ->setScore(0.98010727272727)
                ->setStreet('Avenue de la République')
                ->setName('Avenue de la République')
                ->setPostcode('75011')
                ->setCitycode('75111')
                ->setCity('Paris')
                ->setDistrict('Paris 11e Arrondissement')
                ->setContext('75, Paris, Île-de-France')
                ->setX(654241.06)
                ->setY(6862946.61)
                ->setImportance(0.78118)
        );

        $facts->setPlaceNature(1);

        $complaint->setIdentity($identity)->setFacts($facts);

        $serviceCode = $this->complaintHandler->getAffectedService($complaint);

        $this->assertEquals('55016', $serviceCode);
    }
}
