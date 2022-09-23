<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AttachmentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('attachments', DropzoneType::class, [
                'label' => 'pel.attachments.upload',
                'required' => false,
            ]);
    }
}
