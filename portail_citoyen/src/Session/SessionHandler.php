<?php

declare(strict_types=1);

namespace App\Session;

use App\Generator\GeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class SessionHandler
{
    private const SESSION_ATTRIBUTE_COMPLAINT = 'complaint';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly SerializerInterface $serializer,
        private readonly GeneratorInterface $generator
    ) {
    }

    public function init(): void
    {
        $session = $this->requestStack->getSession();
        if (!$session->has(self::SESSION_ATTRIBUTE_COMPLAINT)) {
            /** @var Uuid $complaintId */
            $complaintId = $this->generator->generate();
            $this->setComplaint(new ComplaintModel($complaintId));
        }
    }

    public function setComplaint(ComplaintModel $complaintModel): void
    {
        $this->requestStack->getSession()->set(
            self::SESSION_ATTRIBUTE_COMPLAINT,
            $this->serializer->serialize($complaintModel, 'json')
        );
    }

    public function getComplaint(): ?ComplaintModel
    {
        if (!$this->requestStack->getSession()->has(self::SESSION_ATTRIBUTE_COMPLAINT)) {
            return null;
        }

        /** @var ComplaintModel $complaint */
        $complaint = $this->serializer->deserialize(
            $this->requestStack->getSession()->get(self::SESSION_ATTRIBUTE_COMPLAINT),
            ComplaintModel::class,
            'json'
        );

        return $complaint;
    }
}
