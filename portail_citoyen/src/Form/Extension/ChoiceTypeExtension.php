<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'rich' => false,
            'inline' => false,
        ]);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (true === $options['expanded']) {
            $view->vars['inline'] = $options['inline'];
            if (false === $options['multiple']) {
                $view->vars['rich'] = $options['rich'];
            }
        }
    }

    public static function getExtendedTypes(): iterable
    {
        return [ChoiceType::class];
    }
}
