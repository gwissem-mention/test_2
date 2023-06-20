<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DropzoneType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'max_files' => 10,
                'max_file_size' => 10,
                'accepted_files' => null,
                'add_remove_links' => true,
                'multiple' => true,
                'label' => 'pel.files',
            ])
            ->setAllowedTypes('max_files', ['null', 'int'])
            ->setAllowedTypes('max_files', ['null', 'int'])
            ->setAllowedTypes('accepted_files', ['null', 'string'])
            ->setAllowedTypes('add_remove_links', 'bool');
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['max_files'] = $options['max_files'];
        $view->vars['max_file_size'] = $options['max_file_size'];
        $view->vars['accepted_files'] = $options['accepted_files'];
        $view->vars['add_remove_links'] = $options['add_remove_links'];
    }

    public function getParent(): string
    {
        return FileType::class;
    }
}
