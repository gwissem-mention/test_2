<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Form\Model\Facts\OffenseDateModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class OffenseDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('exactDateKnown', ChoiceType::class, [
                'label' => 'pel.complaint.exact.date.known',
                'expanded' => true,
                'multiple' => false,
                'inline' => true,
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
            ])
            ->add('choiceHour', ChoiceType::class, [
                'choices' => [
                    'pel.yes.i.know.the.exact.time.of.facts' => 'yes',
                    'pel.no.but.i.know.the.time.slot' => 'maybe',
                    'pel.no.but.i.don.t.know.at.all.the.time.of.facts' => 'no',
                ],
                'expanded' => true,
                'label' => 'pel.do.you.know.hour.facts',
            ]);

        $builder->get('exactDateKnown')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $exactDateKnown = $event->getData();
                if ('' === $exactDateKnown) {
                    return;
                }
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addDateFields($parent, boolval($exactDateKnown));
            }
        );

        $builder->get('choiceHour')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var ?string $choice */
                $choice = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addDateTimeHourField($parent, $choice);
            }
        );
    }

    private function addDateFields(FormInterface $form, ?bool $exactDateKnown = null): void
    {
        if (null === $exactDateKnown) {
            return;
        }
        $form->add('startDate', DateType::class, [
            'constraints' => [
                new NotBlank(),
                new LessThanOrEqual('today', message: 'pel.date.less.than.equal.today.error'),
            ],
            'label' => true === $exactDateKnown ? 'pel.offense.unique.date' : 'pel.offense.start.date',
            'widget' => 'single_text',
            'help' => 'pel.date.help',
        ]);

        if (false === $exactDateKnown) {
            $form->add('endDate', DateType::class, [
                'constraints' => [
                    new NotBlank(),
                    new LessThanOrEqual('today', message: 'pel.date.less.than.equal.today.error'),
                ],
                'label' => 'pel.offense.end.date',
                'widget' => 'single_text',
                'help' => 'pel.date.help',
            ]);
        }
    }

    private function addDateTimeHourField(FormInterface $form, ?string $choice = null): void
    {
        if ('yes' === $choice) {
            $form->add('hour', TimeType::class, [
                'attr' => [
                    'class' => 'fr-btn',
                ],
                'label' => 'pel.exact.hour',
                'widget' => 'single_text',
            ]);
        } elseif ('maybe' === $choice) {
            $form->add('startHour', TimeType::class, [
                'attr' => [
                    'class' => 'fr-btn',
                ],
                'label' => 'pel.start.hour',
                'widget' => 'single_text',
            ]);
            $form->add('endHour', TimeType::class, [
                'attr' => [
                    'class' => 'fr-btn',
                ],
                'label' => 'pel.end.hour',
                'widget' => 'single_text',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'data_class' => OffenseDateModel::class,
        ]);
    }
}
