<?php

declare(strict_types=1);

namespace App\Components\Facts;

use App\Form\Facts\FactsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent('facts')]
class FactsComponent extends AbstractController
{
    use LiveCollectionTrait;
    use DefaultActionTrait;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(FactsType::class);
    }

    #[LiveAction]
    public function submit(): RedirectResponse
    {
        $this->submitForm();

        return $this->redirectToRoute('summary');
    }
}
