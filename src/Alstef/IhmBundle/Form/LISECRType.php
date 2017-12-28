<?php
// src/Alstef/IhmBundle/Form/LisproType.php

namespace Alstef\IhmBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class LISECRType extends AbstractType
{
  private $logger;
  private $bdd;

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $this->logger = $options['container']->get('alstef.logger');
    $this->bdd = $options['container']->get('alstef.bdd');

    $this->logger->info("Début");

    $builder->add('TRMNOM',
                  TextType::class,
                  array('required' => true,
                        'label' => 'LIBELLE.TRMNOM'));
    $builder->add('TRMADR',
                  TextType::class,
                  array('required' => true,
                        'label' => 'LIBELLE.TRMADR'));
    $builder->add('AUTOR_MODIF',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.AUTOR_MODIF'));
    $builder->add('AUTOR_DELETE',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.AUTOR_DELETE'));
    $builder->add('VISIBLE',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.VISIBLE'));
    $builder->add('RPOINT_OPE',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.RPOINT_OPE'));
    $builder->add('CONNECTED',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.CONNECTED'));
    $builder->add('USR_NOM_CONNECTE',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.USR_NOM_CONNECTE'));
    $builder->add('BVOL_USED',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.BVOL_USED'));
    $builder->add('USR_NOM_LAST_LOGIN',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.USR_NOM_LAST_LOGIN'));
    $builder->add('DATCRE_COURTE',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.DATCRE_COURTE'));
    $builder->add('USR_NOM',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.USR_NOM'));
    $builder->add('DATMAJ_COURTE',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.DATMAJ_COURTE'));
    $builder->add('DATUSE_LONGUE',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.DATUSE_LONGUE'));

    $this->logger->info("Fin");
  }

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => 'Alstef\IhmBundle\FormEntity\MSGIHM\LISECR',
      'container' => null,
    ]);

    $resolver->setRequired('container'); // Requires that currentOrg be set by the caller.
  }

  public function getName() {
    return 'LISECR';
  }
}
