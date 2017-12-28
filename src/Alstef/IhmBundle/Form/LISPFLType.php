<?php
// src/Alstef/IhmBundle/Form/LisproType.php

namespace Alstef\IhmBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class LISPFLType extends AbstractType
{
  private $logger;
  private $bdd;

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $this->logger = $options['container']->get('alstef.logger');
    $this->bdd = $options['container']->get('alstef.bdd');

    $this->logger->info("Début");

    $builder->add('PROFIL',
                  TextType::class,
                  array('required' => true,
                        'label' => 'LIBELLE.PROFIL'));
    $builder->add('PROFIL_LIB',
                  TextType::class,
                  array('required' => true,
                        'label' => 'LIBELLE.PROFIL_LIB'));
    $builder->add('NB_USR',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.NB_USR'));
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
    $builder->add('AUTOR_DELETE',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.AUTOR_DELETE'));
    $builder->add('AUTOR_MODIF',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.AUTOR_MODIF'));
    $builder->add('VISIBLE',
                  TextType::class,
                  array('required' => false,
                        'label' => 'LIBELLE.VISIBLE'));
    $this->logger->info("Fin");
  }

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => 'Alstef\IhmBundle\FormEntity\MSGIHM\LISPFL',
      'container' => null,
    ]);

    $resolver->setRequired('container'); // Requires that currentOrg be set by the caller.
  }

  public function getName() {
    return 'LISPFL';
  }
}
