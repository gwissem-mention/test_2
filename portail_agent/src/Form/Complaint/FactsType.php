<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Entity\Facts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Facts $facts */
        $facts = $options['data'];

        $builder
            ->add('natures', ChoiceType::class, [
                'choices' => [
                    'pel.offense.nature.robbery' => Facts::NATURE_ROBBERY,
                    'pel.offense.nature.degradation' => Facts::NATURE_DEGRADATION,
                    'pel.offense.nature.other' => Facts::NATURE_OTHER,
                ],
                'expanded' => true,
                'label' => 'pel.nature.of.the.facts',
                'label_attr' => [
                    'class' => 'checkbox-inline',
                ],
                'multiple' => true,
            ])
            ->add('place', TextType::class, [
                'label' => 'pel.nature.place',
            ])
            ->add('startAddress', TextType::class, [
                'label' => 'pel.address.start.or.exact',
            ])
            ->add('addressAdditionalInformation', TextareaType::class, [
                'label' => 'pel.description',
            ])
            ->add('addressAdditionalInformation', TextareaType::class, [
                'label' => 'pel.description',
            ])
            ->add('exactDateKnown', ChoiceType::class, [
                'label' => 'pel.exact.date.known',
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
            ])
            ->add('startDate', DateType::class, [
                'label' => $facts->isExactDateKnown() ? 'pel.the' : 'pel.between',
                'widget' => 'single_text',
            ])
            ->add('exactHourKnown', ChoiceType::class, [
                'label' => 'pel.do.you.know.hour.facts',
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'pel.yes' => Facts::EXACT_HOUR_KNOWN_YES,
                    'pel.no' => Facts::EXACT_HOUR_KNOWN_NO,
                    'pel.i.dont.know' => Facts::EXACT_HOUR_KNOWN_DONT_KNOW,
                ],
            ]);

        if ($facts->getEndAddress()) {
            $builder->add('endAddress', TextType::class, [
                'label' => 'pel.address.end',
            ]);
        }

        if (!$facts->isExactDateKnown()) {
            $builder->add('endDate', DateType::class, [
                'label' => 'pel.and',
                'widget' => 'single_text',
            ]);
        }

        if (in_array($facts->getExactHourKnown(), [Facts::EXACT_HOUR_KNOWN_YES, Facts::EXACT_HOUR_KNOWN_NO], true)) {
            $builder->add('startHour', TimeType::class, [
                'label' => Facts::EXACT_HOUR_KNOWN_YES === $facts->getExactHourKnown() ? 'pel.at' : 'pel.between',
                'widget' => 'single_text',
            ]);

            if (Facts::EXACT_HOUR_KNOWN_NO === $facts->getExactHourKnown()) {
                $builder->add('endHour', TimeType::class, [
                    'label' => 'pel.and',
                    'widget' => 'single_text',
                ]);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facts::class,
            'disabled' => true,
        ]);
    }
}
