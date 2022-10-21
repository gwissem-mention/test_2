<?php

namespace App\Tests\Session;

use App\Session\ComplaintModel;
use App\Session\ComplaintSessionHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class ComplaintSessionHandlerTest extends KernelTestCase
{
    private readonly ComplaintSessionHandler $complaintSessionHandler;
    private readonly RequestStack $requestStack;
    private readonly SerializerInterface $serializer;

    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var ComplaintSessionHandler $complaintSessionHandler */
        $complaintSessionHandler = $container->get(ComplaintSessionHandler::class);
        $this->complaintSessionHandler = $complaintSessionHandler;

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
        $this->complaintSessionHandler->init();

        $complaint = $this->serializer->deserialize(
            $this->requestStack->getSession()->get('complaint'),
            ComplaintModel::class,
            'json'
        );

        $this->assertInstanceOf(ComplaintModel::class, $complaint);
    }

    public function testGet(): void
    {
        $this->requestStack->getSession()->set(
            'complaint',
            $this->serializer->serialize(new ComplaintModel(Uuid::v1()), 'json')
        );

        $this->assertNotNull($this->complaintSessionHandler->get());
    }
}
