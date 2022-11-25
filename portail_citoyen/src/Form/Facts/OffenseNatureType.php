<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Enum\OffenseNature;
use App\Form\Model\Facts\OffenseNatureModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class OffenseNatureType extends AbstractType
{
    private const OTHER_AAB_TEXT_MAX_LENGTH = 800;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('offenseNatures', ChoiceType::class, [
                'choices' => OffenseNature::getChoices(),
                'constraints' => [new NotBlank()],
                'expanded' => true,
                'inline' => true,
                'label' => 'pel.complaint.nature.of.the.facts',
                'multiple' => true,
                'placeholder' => 'pel.complaint.nature.of.the.facts',
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var ?OffenseNatureModel $offenseNature */
                    $offenseNature = $event->getData();
                    $offenseNatures = $offenseNature?->getOffenseNatures();
                    if (null === $offenseNatures) {
                        return;
                    }
                    $this->addAabTextField($event->getForm(), $offenseNatures);
                }
            )
            ->get('offenseNatures')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    /** @var FormInterface $parent */
                    $parent = $event->getForm()->getParent();
                    /** @var ?OffenseNatureModel $offenseNatureModel */
                    $offenseNatureModel = $parent->getData();
                    /** @var array<int, int> $data */
                    $data = $event->getForm()->getData();
                    $this->addAabTextField($parent, $data, $offenseNatureModel);
                }
            );
    }

    /**
     * @param array<int, int> $offenseNatures
     */
    private function addAabTextField(
        FormInterface $form,
        array $offenseNatures,
        ?OffenseNatureModel $offenseNatureModel = null
    ): void {
        if (true === in_array(OffenseNature::Other->value, $offenseNatures)) {
            $form->add('aabText', TextareaType::class, [
                'label' => 'pel.complaint.offense.nature.other.aab.text',
                'attr' => [
                    'maxlength' => self::OTHER_AAB_TEXT_MAX_LENGTH,
                ],
                'constraints' => [new Length(['max' => self::OTHER_AAB_TEXT_MAX_LENGTH])],
            ]);
        } else {
            $form->remove('aabText');
            $offenseNatureModel?->setAabText(null);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OffenseNatureModel::class,
        ]);
    }
}
