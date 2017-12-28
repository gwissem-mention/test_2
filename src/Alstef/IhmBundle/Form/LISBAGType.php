<?php
// src/Alstef/IhmBundle/Form/LisproType.php

namespace Alstef\IhmBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class LISBAGType extends AbstractType
{
  private $logger;

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $this->logger = $options['container']->get('alstef.logger');
    $this->logger->info("Début");

    $dDebut = new \DateTime("now");
    $dDebut->setTime(00, 00, 00);
    $dFin = new \DateTime("now");
    $dFin->setTime(23, 59, 59);
    
    $builder->add ('date_debut',
                    DateTimeType::class,
                    array (
                      'required' => false,
                      'data' =>  $dDebut,
                      'format' => 'yyyy/MM/dd-HH:mm:ss',
                      'label' => 'LIBELLE.DATDEB_LONGUE',
                      'label_attr' => array ('class' => 'control-label col-md-3 col-sm3 col-xs12 '),
                      'attr' => array ('class' => 'form_datetime'),
                      'widget' => 'single_text',
                      'html5' => false));
    $builder->add ('date_fin',
                    DateTimeType::class,
                    array (
                      'required' => false,
                      'data' => $dFin,
                      'format' => 'yyyy/MM/dd-HH:mm:ss',
                      'label' => 'LIBELLE.DATFIN_LONGUE',
                      'label_attr' => array ('class' => 'control-label col-md-3 col-sm3 col-xs12'),
                      'attr' => array ('class' => 'form_datetime'),
                      'widget' => 'single_text',
                      'html5' => false));
    $builder->add ('bsm_ok',
                    CheckboxType::class,
                    array (
                      'required' => false,
                      'data' => true,
                      'label' => 'LIBELLE.TAG_OK',
                      'label_attr' => array ('class' => 'control-label col-md-3 col-sm3 col-xs12')));
    $builder->add ('bsm_double',
                    CheckboxType::class,
                    array (
                      'required' => false,
                      'data' => false,
                      'label' => 'LIBELLE.TAG_DBL',
                      'label_attr' => array ('class' => 'control-label col-md-3 col-sm3 col-xs12')));
    $builder->add ('bsm_delete',
                    CheckboxType::class,
                    array (
                      'required' => false,
                      'data' => false,
                      'label' => 'LIBELLE.TAG_DEL',
                      'label_attr' => array ('class' => 'control-label col-md-3 col-sm3 col-xs12')));

    $this->logger->info("Fin");
  }

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver)
  {
      $resolver->setDefaults([
              'data_class' => 'Alstef\IhmBundle\FormEntity\LISBAG',
              'container' => null,
      ]);

      $resolver->setRequired('container'); // Requires that currentOrg be set by the caller.
  }

  public function getName() {
    return 'LISBAG';
  }
}
