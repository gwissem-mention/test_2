<?php

declare(strict_types=1);

namespace App\Form\Objects;

use App\Form\Model\Objects\ObjectModel;
use App\Form\Model\Objects\ObjectsModel;
use App\Session\SessionHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ObjectsModel::class,
             'attr' => [
                'novalidate' => true,
            ],
        ]);
    }
}
