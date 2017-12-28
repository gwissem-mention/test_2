<?php
// src/Alstef/IhmBundle/Form/LisproType.php

namespace Alstef\IhmBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;



class LISVOLType extends AbstractType
{
  private $logger;

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $this->logger = $options['container']->get('alstef.logger');

    $this->logger->info("Début");

    $builder->add( 'sdd_vol',
                    DateTimeType::class,
                    array (
                      'required' => false,
                      'placeholder' => 'YYYY/MM/DD-HH:MI:SS',
                      'label' => 'LIBELLE.SDD_VOL',
                      'label_attr' => array ('class' => 'control-label col-md-3 col-sm3 col-xs12 '),
                      'attr' => array ('class' => 'form_datetime'),
                      'widget' => 'single_text',
                      'html5' => false));
    $builder->add(  'vol_du_jour',
                    CheckboxType::class,
                    array (
                      'required' => false,
                      'data' => true,
                      'label' => 'LIBELLE.VOL_DU_JOUR',
                      'label_attr' => array ('class' => 'control-label col-md-3 col-sm3 col-xs12')));
    $builder->add(  'vol_affecte',
                    CheckboxType::class,
                    array (
                      'required' => false,
                      'data' => false,
                      'label' => 'LIBELLE.VOL_AFFECTE',
                      'label_attr' => array ('class' => 'control-label col-md-3 col-sm3 col-xs12')));
    $builder->add(  'vol_nonaffecte',
                    CheckboxType::class,
                    array (
                      'required' => false,
                      'data' => false,
                      'label' => 'LIBELLE.VOL_NONAFFECTE',
                      'label_attr' => array ('class' => 'control-label col-md-3 col-sm3 col-xs12')));
    $builder->add(  'en_warning',
                    CheckboxType::class,
                    array (
                      'required' => false,
                      'data' => false,
                      'label' => 'LIBELLE.EN_WARNING',
                      'label_attr' => array ('class' => 'control-label col-md-3 col-sm3 col-xs12')));
    $builder->add(  'en_erreur',
                    CheckboxType::class,
                    array (
                      'required' => false,
                      'data' => false,
                      'label' => 'LIBELLE.EN_ERREUR',
                      'label_attr' => array ('class' => 'control-label col-md-3 col-sm3 col-xs12')));

  }

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver)
  {
      $resolver->setDefaults([
              'data_class' => 'Alstef\IhmBundle\FormEntity\LISVOL',
              'container' => null,
      ]);

      $resolver->setRequired('container'); // Requires that currentOrg be set by the caller.
  }

  public function getName() {
    return 'LISVOL';
  }
}
