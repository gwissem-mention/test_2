<?php

declare(strict_types=1);

namespace App\Tests\Session;

use App\AppEnum\Gender;
use App\Form\Model\Identity\CivilStateModel;
use App\Form\Model\Identity\ContactInformationModel;
use App\Form\Model\Identity\IdentityModel;
use App\Form\Model\LocationModel;
use App\Security\User;
use App\Session\ComplaintModel;
use App\Session\FranceConnectHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class FranceConnectHandlerTest extends KernelTestCase
{
    private readonly FranceConnectHandler $franceConnectHandler;
    private readonly SerializerInterface $serializer;
    private readonly RequestStack $requestStack;

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $container = static::getContainer();

        /** @var FranceConnectHandler $franceConnectHandler */
        $franceConnectHandler = $container->get(FranceConnectHandler::class);
        $this->franceConnectHandler = $franceConnectHandler;

        /** @var RequestStack $requestStack */
        $requestStack = $container->get(RequestStack::class);
        $this->requestStack = $requestStack;

        /** @var SerializerInterface $serializer */
        $serializer = $container->get(SerializerInterface::class);
        $this->serializer = $serializer;

        $this->requestStack->push(new Request());
        $this->requestStack->getCurrentRequest()?->setSession(
            new Session(new MockArraySessionStorage())
        );

        $user = new User(
            'jean-dupond-id',
            'DUPOND',
            'Jean',
            '',
            '1982-04-23',
            '75107',
            '99160',
            Gender::MALE->value,
            'jean.dupond@example.org',
        );
        $token = new TestBrowserToken($user->getRoles(), $user);
        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = $container->get('security.untracked_token_storage');
        $tokenStorage->setToken($token);
    }

    public function testSetIdentityToComplaint(): void
    {
        $this->initComplaintModelSession();

        $this->franceConnectHandler->setIdentityToComplaint();

        $complaint = $this->getComplaintModelFromSession();

        $identity = $complaint->getIdentity();
        /** @var ?CivilStateModel $civilState */
        $civilState = $identity?->getCivilState();
        /** @var ?ContactInformationModel $contactInformation */
        $contactInformation = $identity?->getContactInformation();
        /** @var ?LocationModel $birthLocation */
        $birthLocation = $civilState?->getBirthLocation();

        $this->assertInstanceOf(ComplaintModel::class, $complaint);
        $this->assertInstanceOf(IdentityModel::class, $identity);
        $this->assertInstanceOf(CivilStateModel::class, $civilState);
        $this->assertInstanceOf(LocationModel::class, $birthLocation);
        $this->assertInstanceOf(ContactInformationModel::class, $contactInformation);

        $this->assertEquals('Jean', $civilState->getFirstnames());
        $this->assertEquals('DUPOND', $civilState->getBirthName());
        $this->assertEquals('1982-04-23', $civilState->getBirthDate()?->format('Y-m-d'));
        $this->assertEquals(1, $civilState->getCivility());
        // FIXME: referential mapping needed
        // $this->assertEquals('Paris (75)', $birthLocation->getFrenchTown());
        $this->assertEquals('jean.dupond@example.org', $contactInformation->getEmail());
    }

    public function testClear(): void
    {
        $this->initComplaintModelSession();

        $this->franceConnectHandler->clear();

        $complaint = $this->getComplaintModelFromSession();

        $this->assertNull($complaint->getIdentity());
        $this->assertFalse($complaint->isFranceConnected());
    }

    private function initComplaintModelSession(): void
    {
        $this->requestStack->getSession()->set(
            'complaint',
            $this->serializer->serialize(new ComplaintModel(Uuid::v1()), 'json')
        );
    }

    private function getComplaintModelFromSession(): ComplaintModel
    {
        /** @var ComplaintModel $complaint */
        $complaint = $this->serializer->deserialize(
            $this->requestStack->getSession()->get('complaint'),
            ComplaintModel::class,
            'json'
        );

        return $complaint;
    }
}
