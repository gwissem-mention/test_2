<?php

declare(strict_types=1);

namespace App\Form;

use App\Session\ComplaintModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotNull;

class LawRefresherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lawRefresherAccepted', CheckboxType::class, [
                'label' => 'pel.law.refresher.acceptance.label',
                'help' => 'pel.law.refresher.acceptance.text',
                'constraints' => [
                    new NotNull(null, 'pel.law.refresher.acceptance.error'),
                    new IsTrue(null, 'pel.law.refresher.acceptance.error'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => ComplaintModel::class,
           'attr' => [
               'novalidate' => true,
           ],
        ]);
    }
}
