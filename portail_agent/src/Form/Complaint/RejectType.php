<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Entity\Complaint;
use App\Repository\RejectReasonRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RejectType extends AbstractType
{
    private const REFUSAL_REASON_OTHER = 'pel-other';

    public function __construct(private RejectReasonRepository $rejectReasonRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('refusalReason', ChoiceType::class, [
                    'label' => 'pel.refusal.reason',
                    'choices' => $this->getRejectReasons(),
                ]
            )
            ->add('refusalText', TextareaType::class, [
                'label' => 'pel.comment',
                'label_attr' => [
                    'class' => 'mt-3',
                ],
                'required' => false,
                'attr' => [
                    'minlength' => 100,
                    'maxlength' => 3000,
                    'data-counter-target' => 'parent',
                ],
                'constraints' => [
                    new Length(['min' => 100, 'max' => 3000]),
                    new Callback([
                        'callback' => static function (?string $value, ExecutionContextInterface $context) {
                            /** @var Form $form */
                            $form = $context->getObject();
                            /** @var Form $formParent */
                            $formParent = $form->getParent();
                            /** @var int $refusalReason */
                            $refusalReason = $formParent->get('refusalReason')->getData();

                            if (self::REFUSAL_REASON_OTHER == $refusalReason && null === $value) {
                                $context->addViolation('This value should not be blank.');
                            }
                        },
                    ]),
                ],
            ]);
    }

    /**
     * @return array<string, string>
     */
    private function getRejectReasons(): array
    {
        $choices = [];
        $rejectReasons = $this->rejectReasonRepository->findAll();
        foreach ($rejectReasons as $reason) {
            $choices[$reason->getLabel()] = $reason->getCode();
        }

        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Complaint::class,
        ]);
    }
}
