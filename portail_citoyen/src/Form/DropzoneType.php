<?php

namespace App\Form;

use App\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\File as HttpFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DropzoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(
            new CallbackTransformer(
                static function (): void {},
                static fn (array $uploadedFiles): array => self::reversedTransformUploadedFiles($uploadedFiles)
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'max_files' => 2,
                'max_file_size' => 2,
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

    /**
     * @param array<HttpFile> $uploadedFiles
     *
     * @return array<File>
     */
    private static function reversedTransformUploadedFiles(array $uploadedFiles): array
    {
        return array_map(
            static fn (HttpFile $uploadedFile) => (new File())->setFile($uploadedFile),
            $uploadedFiles
        );
    }
}
