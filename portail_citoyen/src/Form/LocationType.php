<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\Model\LocationModel;
use App\Thesaurus\TownAndDepartmentThesaurusProviderInterface;
use App\Thesaurus\Transformer\TownToTransformTransformerInterface;
use App\Tmp\CountryInseeCodeMapper;
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
    private ?LocationModel $location;

    public function __construct(
        private readonly TownAndDepartmentThesaurusProviderInterface $townAndDepartmentAndDepartmentThesaurusProvider,
        private readonly TownToTransformTransformerInterface $townToTransformTransformer,
        private readonly string $franceCode,
        private readonly CountryInseeCodeMapper $countryInseeCodeMapper,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ?LocationModel $fcData */
        $fcData = $options['fc_data'];
        $this->location = $fcData;

        // Temp fix to handle France case
        // TODO remove it when countries thesaurus is implemented
        if ($this->countryInseeCodeMapper->isSupportedInseeCode($this->location?->getCountry())) {
            $this->location?->setCountry($this->countryInseeCodeMapper->getCountryCode($this->location->getCountry()));
        }

        $builder
            ->add('country', CountryType::class, [
                'label' => $options['country_label'],
                'preferred_choices' => [$this->franceCode],
                'empty_data' => null === $this->location ? $this->franceCode : $this->location->getCountry(),
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var ?LocationModel $location */
                $location = $event->getData();
                $this->addTownField($event->getForm(), $location?->getCountry());
            }
        );

        $builder->get('country')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var string $country */
                $country = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var ?LocationModel $locationModel */
                $locationModel = $parent->getData();
                $this->addTownField($parent, $country, $locationModel);
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
            'fc_data' => null,
        ]);
    }

    private function addTownField(FormInterface $form, ?string $country, ?LocationModel $locationModel = null): void
    {
        if ($this->franceCode === $country) {
            $this->addFormPartForFrenchPlace($form, $locationModel);
        } else {
            $this->addFormPartForForeignPlace($form, $locationModel);
        }
    }

    private function addFormPartForForeignPlace(FormInterface $form, ?LocationModel $locationModel = null): void
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

        $locationModel?->setFrenchTown(null)->setDepartment(null);
    }

    private function addFormPartForFrenchPlace(FormInterface $form, ?LocationModel $locationModel = null): void
    {
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
                'empty_data' => $this->location?->getFrenchTown(),
            ])
            ->add('department', TextType::class, [
                'disabled' => true,
                'label' => $form->getConfig()->getOption('department_label'),
            ]);

        $locationModel?->setOtherTown(null);
    }
}
