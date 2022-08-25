<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Enum\DeclarantStatus;
use RuntimeException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class IdentityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('declarantStatus', ChoiceType::class, [
                'label' => 'complaint.identity.declarant.status',
                'expanded' => true,
                'multiple' => false,
                'rich' => true,
                'choices' => DeclarantStatus::getChoices(),
                'choice_attr' => function () {
                    return [
                        'data-controller' => 'complaint--identity spinner',
                        'data-action' => 'complaint--identity#displayForm complaint--identity:spinner_show->spinner#show',
                    ];
                },
            ])
            ->get('declarantStatus')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'onDeclarantStatusPostSubmit']
            );
    }

    public function onDeclarantStatusPostSubmit(FormEvent $event): void
    {
        $label = match (intval($event->getData())) {
            DeclarantStatus::Victim->value => 'complaint.identity.victim',
            DeclarantStatus::CorporationLegalRepresentative->value => 'complaint.identity.corporation.legal.representative',
            DeclarantStatus::PersonLegalRepresentative->value => 'complaint.identity.person.legal.representative',
            default => throw new RuntimeException(sprintf('Choice %d not allowed', intval($event->getData())))
        };

        $event->getForm()->getParent()?->add('identity', FormType::class, [
            'label' => $label,
        ]);
    }
}
