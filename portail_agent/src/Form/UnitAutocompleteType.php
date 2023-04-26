<?php

declare(strict_types=1);

namespace App\Form;

use App\Referential\Entity\Unit;
use App\Referential\Repository\UnitRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Hoa\Compiler\Llk\Rule\Choice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;

#[AsEntityAutocompleteField]
class UnitAutocompleteType extends AbstractType
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UnitRepository $unitRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addModelTransformer(new CallbackTransformer(
                function (?string $unitName) {
                    return $this->unitRepository->findOneBy(['name' => $unitName])?->getCode();
                },
                function (?string $unitCode) {
                    return $unitCode;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Unit::class,
            'autocomplete' => true,
            'preload' => false,
            // 'filter_query' => function (QueryBuilder $qb, string $query, EntityRepository $entityRepository) {
            //    if (empty($query)) {
            //        //return $qb->andWhere('0 = 1');
            //        return;
            //    }
            //
            //    return $qb
            //        ->andWhere('LOWER(entity.name) LIKE LOWER(:name)')
            //        ->orderBy('entity.name', 'ASC')
            //        ->setParameter('name', '%'.$query.'%');
            // },
            'autocomplete_url' => $this->urlGenerator->generate('ux_entity_autocomplete', [
                'alias' => 'unit',
            ]),
            // 'security' => 'IS_AUTHENTICATED',
            // 'choice_label' => 'name',
            // 'choice_value' => 'code ',
            'label' => 'pel.unit.name',
            'placeholder' => false,
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'autocomplete';
    }
}
