<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\RightDelegation;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class RightDelegationType extends AbstractType
{
    public function __construct(private readonly UserRepository $userRepository, private readonly Security $security)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $choices = $this->userRepository->getAgentsByService($user->getServiceCode());

        $builder
            ->add('startDate', DateType::class, [
            'attr' => [
                'class' => 'js-datepicker',
            ],
            'input' => 'datetime_immutable',
            'widget' => 'single_text',
            'view_timezone' => 'UTC',
            'label' => false,
            'constraints' => [
                new NotBlank(),
                new GreaterThanOrEqual('today', message: 'pel.date.greater.than.equal.today.error'),
            ],
        ])
            ->add('endDate', DateType::class, [
            'attr' => [
                'class' => 'js-datepicker',
            ],
            'input' => 'datetime_immutable',
            'widget' => 'single_text',
            'view_timezone' => 'UTC',
            'label' => false,
            'constraints' => [
                new NotBlank(),
                new GreaterThanOrEqual('today', message: 'pel.date.greater.than.equal.today.error'),
            ],
        ])
            ->add('delegatedAgents', EntityType::class, [
            'class' => User::class,
            'choices' => $choices,
            'label' => 'pel.right.delegation',
            'choice_label' => 'appellation',
            'multiple' => true,
            'expanded' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RightDelegation::class,
        ]);
    }
}
