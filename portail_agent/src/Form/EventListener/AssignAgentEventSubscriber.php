<?php

namespace App\Form\EventListener;

use App\Entity\Complaint;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

abstract class AssignAgentEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function preSetData(FormEvent $event): void
    {
        /** @var Complaint $complaint */
        $complaint = $event->getData();
        $this->buildAgentField($event->getForm(), $complaint->getAssignedTo());
    }

    public function preSubmit(PreSubmitEvent $event): void
    {
        /** @var array<string, string> $data */
        $data = $event->getData();
        $user = $data['assignedTo'];
        $this->buildAgentField($event->getForm(), $this->userRepository->find($user));
    }

    abstract public function buildAgentField(FormInterface $form, ?User $user): void;
}
