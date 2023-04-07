<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DropZoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', \Symfony\UX\Dropzone\Form\DropzoneType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'pel.drag.and.drop.or.click.here.to.browse',
                    'data-complaint-target' => 'dropzoneFile',
                ],
            ])
        ;
    }
}
