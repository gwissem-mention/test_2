<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Form\DropzoneType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class SendReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('files', DropzoneType::class, [
                'label' => false,
                'accepted_files' => 'image/jpeg,image/png,application/pdf',
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }
}
