<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Form\Model\Facts\AddressModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isAddressOrRouteFactsKnown', ChoiceType::class, [
                'label' => 'pel.address.or.route.facts',
                'expanded' => true,
                'multiple' => false,
                'inline' => true,
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
            ])
            ->add('addressAdditionalInformation', TextareaType::class, [
                'label' => 'pel.additional.place.information',
                'required' => false,
                'help' => 'pel.additional.place.information.help',
            ]);

        $builder
            ->get('isAddressOrRouteFactsKnown')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $choice = $event->getForm()->getData();
                    if ('' === $choice) {
                        return;
                    }
                    /** @var FormInterface $parent */
                    $parent = $event->getForm()->getParent();
                    $this->addOffenseNatureOrNotKnownField($parent, boolval($choice));
                }
            );
    }

    private function addOffenseNatureOrNotKnownField(FormInterface $form, bool $choice): void
    {
        if (true === $choice) {
            $form
                ->add('startAddress', TextType::class, [
                    'label' => 'pel.address.start.or.exact',
                    'help' => 'pel.address.start.or.exact.help',
                    'constraints' => [
                        new NotBlank(),
                    ],
                ])
                ->add('endAddress', TextType::class, [
                    'required' => false,
                    'label' => 'pel.address.end',
                    'help' => 'pel.address.end.help',
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddressModel::class,
        ]);
    }
}
