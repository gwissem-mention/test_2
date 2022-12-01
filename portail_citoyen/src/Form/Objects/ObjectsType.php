<?php

declare(strict_types=1);

namespace App\Form\Objects;

use App\Form\Model\Objects\ObjectModel;
use App\Form\Model\Objects\ObjectsModel;
use App\Session\SessionHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class ObjectsType extends AbstractType
{
    public function __construct(
        private readonly SessionHandler $sessionHandler,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objects', LiveCollectionType::class, [
                'entry_type' => ObjectType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'pel.objects',
                'label_attr' => [
                    'class' => 'fr-h6',
                ],
                'button_add_options' => [
                    'label' => 'pel.objects.add',
                    'attr' => [
                        'class' => 'fr-btn fr-btn--secondary',
                    ],
                ],
                'button_delete_options' => [
                    'label' => 'pel.delete',
                    'attr' => [
                        'class' => 'fr-btn fr-btn--tertiary-no-outline',
                    ],
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                'data' => $this->sessionHandler->getComplaint()?->getObjects()?->getObjects() ?: new ArrayCollection(
                    [new ObjectModel()]
                ),
            ])
            ->add('amountKnown', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.is.amount.known',
                'multiple' => false,
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var ObjectsModel $objectsModel */
                    $objectsModel = $event->getData();
                    $form = $event->getForm();
                    $this->addAmountKnownField($form, $objectsModel->isAmountKnown());
                }
            );

        $builder->get('amountKnown')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var bool $choice */
                $choice = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var ObjectsModel $objectsModel */
                $objectsModel = $parent->getData();
                $this->addAmountKnownField($parent, boolval($choice), $objectsModel);
            }
        );
    }

    private function addAmountKnownField(FormInterface $form, ?bool $choice, ?ObjectsModel $objectsModel = null): void
    {
        if (true === $choice) {
            $form->add('amount', MoneyType::class, [
                'label' => 'pel.amount',
                'scale' => 0,
                'currency' => false,
                'html5' => true,
                'attr' => [
                    'min' => 1,
                    'max' => 999999999999,
                ],
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => 12,
                    ]),
                ],
            ]);
        } else {
            $form->remove('amount');
            $objectsModel?->setAmount(null);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ObjectsModel::class,
        ]);
    }
}
