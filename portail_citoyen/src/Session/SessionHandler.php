<?php

declare(strict_types=1);

namespace App\Session;

use App\Form\Model\Facts\FactsModel;
use App\Form\Model\Identity\IdentityModel;
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
        private readonly GeneratorInterface $generator,
    ) {
    }

    public function init(): void
    {
        $session = $this->requestStack->getSession();
        if (!$session->has(self::SESSION_ATTRIBUTE_COMPLAINT)) {
            /** @var Uuid $complaintId */
            $complaintId = $this->generator->generate();
            $this->setComplaint($this->initComplaint($complaintId));
        }
    }

    private function initComplaint(Uuid $complaintId): ComplaintModel
    {
        $complaint = new ComplaintModel($complaintId);
        $complaint->setIdentity(new IdentityModel());
        $complaint->setFacts(new FactsModel());

        return $complaint;
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

        $sessionComplaint = $this->requestStack->getSession()->get(self::SESSION_ATTRIBUTE_COMPLAINT);

        /** @var ComplaintModel $complaint */
        $complaint = $this->serializer->deserialize($sessionComplaint, ComplaintModel::class, 'json');

        return $complaint;
    }
}
