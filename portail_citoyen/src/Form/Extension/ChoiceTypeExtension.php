<?php

declare(strict_types=1);

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
            'choices_help' => [],
            'choices_img' => [],
        ]);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (true === $options['expanded']) {
            $view->vars['inline'] = $options['inline'];
            $view->vars['choices_help'] = $options['choices_help'];
            $view->vars['choices_img'] = $options['choices_img'];
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
