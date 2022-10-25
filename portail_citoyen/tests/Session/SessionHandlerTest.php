<?php

namespace App\Tests\Session;

use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class SessionHandlerTest extends KernelTestCase
{
    private readonly SessionHandler $sessionHandler;
    private readonly RequestStack $requestStack;
    private readonly SerializerInterface $serializer;

    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var SessionHandler $sessionHandler */
        $sessionHandler = $container->get(SessionHandler::class);
        $this->sessionHandler = $sessionHandler;

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
    }

    public function testInit(): void
    {
        $this->sessionHandler->init();

        $complaint = $this->getComplaintModelFromSession();

        $this->assertInstanceOf(ComplaintModel::class, $complaint);
    }

    public function testSetComplaint(): void
    {
        $this->sessionHandler->setComplaint(new ComplaintModel(Uuid::v1()));

        $complaint = $this->getComplaintModelFromSession();

        $this->assertInstanceOf(ComplaintModel::class, $complaint);
    }

    public function testGetComplaint(): void
    {
        $this->requestStack->getSession()->set(
            'complaint',
            $this->serializer->serialize(new ComplaintModel(Uuid::v1()), 'json')
        );

        $this->assertNotNull($this->sessionHandler->getComplaint());
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
