<?php

declare(strict_types=1);

namespace App\Form\Complaint;

// Hidden for experimentation PEL-1590

// use App\Form\DTO\BulkReassignAction;
// use App\Form\UnitAutocompleteType;
// use App\Referential\Entity\Unit;
// use App\Referential\Repository\UnitRepository;
use Symfony\Component\Form\AbstractType;

// use Symfony\Component\Form\Extension\Core\Type\HiddenType;
// use Symfony\Component\Form\Extension\Core\Type\TextareaType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\Form\FormEvent;
// use Symfony\Component\Form\FormEvents;
// use Symfony\Component\Form\FormInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Validator\Constraints\NotBlank;

class BulkReassignType extends AbstractType
{
    //    public function __construct(private readonly UnitRepository $unitRepository)
    //    {
    //    }
    //
    //    public function buildForm(FormBuilderInterface $builder, array $options): void
    //    {
    //        $builder
    //            ->addEventListener(
    //                FormEvents::PRE_SET_DATA,
    //                function (FormEvent $event) {
    //                    /** @var BulkReassignAction $bulkReassignAction */
    //                    $bulkReassignAction = $event->getData();
    //                    $this->addUnitFields(
    //                        $event->getForm(),
    //                        $this->unitRepository->findOneBy(['code' => $bulkReassignAction->getUnitCodeToReassign() ?? null])
    //                    );
    //                }
    //            )
    //            ->addEventListener(
    //                FormEvents::PRE_SUBMIT,
    //                function (FormEvent $event) {
    //                    /** @var array<string, string> $data */
    //                    $data = $event->getData();
    //                    $unitCode = $data['unitCodeToReassign'];
    //                    $this->addUnitFields(
    //                        $event->getForm(),
    //                        $this->unitRepository->findOneBy(['code' => $unitCode])
    //                    );
    //                }
    //            );
    //    }
    //
    //    private function addUnitFields(FormInterface $form, ?Unit $unit): void
    //    {
    //        $form->add('unitCodeToReassign', UnitAutocompleteType::class, [
    //            'choices' => null !== $unit ? [$unit->getName() => $unit->getCode()] : null,
    //            'attr' => [
    //                'required' => true,
    //            ],
    //            'constraints' => [
    //                new NotBlank(),
    //            ],
    //        ])
    //            ->add('reassignText', TextareaType::class, [
    //                'label' => 'pel.unit.reassignment.reason',
    //                'required' => true,
    //                'constraints' => [
    //                    new NotBlank(),
    //                ],
    //            ])
    //            ->add('complaints', HiddenType::class, [
    //                'attr' => [
    //                    'data-dashboard-bulk-action-target' => 'bulkReassignComplaints',
    //                ],
    //            ]);
    //    }
    //
    //    public function configureOptions(OptionsResolver $resolver): void
    //    {
    //        $resolver->setDefaults([
    //            'data_class' => BulkReassignAction::class,
    //        ]);
    //    }
}
