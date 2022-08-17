<?php

declare(strict_types=1);

namespace App\Form\Complaint\Infraction;

use App\Enum\OffenseNature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Length;

class OffenseNatureType extends AbstractType
{
    private const OTHER_AAB_TEXT_MAX_LENGTH = 800;

    public function __construct(private readonly RouterInterface $router)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('offenseNature', ChoiceType::class, [
                'placeholder' => 'complaint.offense.nature',
                'label' => 'complaint.offense.nature',
                'choices' => OffenseNature::getChoices(),
                'attr' => [
                    'class' => 'fr-select',
                    'data-controller' => 'complaint--offense-nature',
                    'data-action' => 'complaint--offense-nature#displayWarningText complaint--offense-nature#displayOtherAABText',
                    'data-complaint--offense-nature-url' => $this->router->generate(
                        'complaint_offense_nature_warning_text'
                    ),
                ],
                'label_attr' => [
                    'class' => 'fr-label',
                ],
                'choice_attr' => static function (?int $choice) {
                    return self::getOffenseNatureChoiceAttr($choice);
                },
            ])
            ->get('offenseNature')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'onOffenseNaturePostSubmit']
            );
    }

    public function onOffenseNaturePostSubmit(FormEvent $event): void
    {
        if (OffenseNature::OtherAab->value === intval($event->getData())) {
            $event->getForm()->getParent()?->add('aabText', TextareaType::class, [
                'label' => 'complaint.offense.nature.other.aab.text',
                'attr' => [
                    'class' => 'fr-input',
                    'maxlength' => self::OTHER_AAB_TEXT_MAX_LENGTH,
                ],
                'label_attr' => [
                    'class' => 'fr-label',
                ],
                'constraints' => [new Length(['max' => self::OTHER_AAB_TEXT_MAX_LENGTH])],
            ]);
        }
    }

    /**
     * @return array<string, int>
     */
    private static function getOffenseNatureChoiceAttr(?int $offenseNature): array
    {
        $attr = [];
        if (is_int($offenseNature)) {
            if (in_array($offenseNature, [
                OffenseNature::HouseRobbery->value,
                OffenseNature::MotorizedVehicleRobbery->value,
                OffenseNature::MotorizedVehicleTheft->value,
                OffenseNature::Scam->value,
            ], true)) {
                $attr['data-complaint-offense-nature-active'] = 1;
            }

            if (OffenseNature::OtherAab->value === $offenseNature) {
                $attr['data-complaint-offense-nature-other-aab'] = 1;
            }
        }

        return $attr;
    }
}
