<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\Model\LocationModel;
use App\Thesaurus\TownAndDepartmentThesaurusProviderInterface;
use App\Thesaurus\Transformer\TownToTransformTransformerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LocationType extends AbstractType
{
    public function __construct(
        private readonly TownAndDepartmentThesaurusProviderInterface $townAndDepartmentAndDepartmentThesaurusProvider,
        private readonly TownToTransformTransformerInterface $townToTransformTransformer,
        private readonly string $franceCode
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $emptyData = $options['empty_data'];
        $builder
            ->add('country', CountryType::class, [
                'label' => $options['country_label'],
                'preferred_choices' => [$this->franceCode],
                'empty_data' => $emptyData instanceof LocationModel ? $emptyData->getCountry() : $this->franceCode,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        $builder->get('country')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var string $country */
                $country = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addTownField($parent, $country);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LocationModel::class,
            'country_label' => false,
            'town_label' => false,
            'department_label' => false,
        ]);
    }

    private function addTownField(FormInterface $form, string $country): void
    {
        if ($this->franceCode === $country) {
            $this->addFormPartForFrenchPlace($form);
        } else {
            $this->addFormPartForForeignPlace($form);
        }
    }

    private function addFormPartForForeignPlace(FormInterface $form): void
    {
        $form
            ->remove('frenchTown')
            ->remove('department')
            ->add('otherTown', TextType::class, [
                'attr' => [
                    'maxlength' => 50,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 50]),
                ],
                'label' => $form->getConfig()->getOption('town_label'),
            ]);
    }

    private function addFormPartForFrenchPlace(FormInterface $form): void
    {
        $emptyData = $form->getConfig()->getEmptyData();
        $form
            ->remove('otherTown')
            ->add('frenchTown', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->townToTransformTransformer->transform(
                    $this->townAndDepartmentAndDepartmentThesaurusProvider->getChoices()
                ),
                'label' => $form->getConfig()->getOption('town_label'),
                'placeholder' => 'pel.choose.your.town',
                'empty_data' => $emptyData instanceof LocationModel ? $emptyData->getFrenchTown() : null,
            ])
            ->add('department', TextType::class, [
                'disabled' => true,
                'label' => $form->getConfig()->getOption('department_label'),
            ]);
    }
}
