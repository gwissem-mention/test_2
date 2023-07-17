<?php

declare(strict_types=1);

namespace App\Form\AdditionalInformation;

use App\Form\Model\AdditionalInformation\WitnessModel;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class WitnessesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                /** @var ArrayCollection<int, WitnessModel> $witnesses */
                $witnesses = $form->getData();
                if ($witnesses->isEmpty()) {
                    $event->getForm()->setData(new ArrayCollection([new WitnessModel()]));
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entry_type' => WitnessType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'label' => false,
            'label_attr' => [
                'class' => 'fr-h6',
            ],
            'button_add_options' => [
                'label' => 'pel.witness.add',
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
        ]);
    }

    public function getParent(): string
    {
        return LiveCollectionType::class;
    }
}
