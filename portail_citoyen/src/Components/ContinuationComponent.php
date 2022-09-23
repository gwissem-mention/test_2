<?php

declare(strict_types=1);

namespace App\Components;

use App\Form\ContinuationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('continuation')]
class ContinuationComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ContinuationType::class);
    }

    #[LiveAction]
    public function submit(): RedirectResponse
    {
        $this->submitForm();

        return $this->redirectToRoute('home_unfolding');
    }
}
