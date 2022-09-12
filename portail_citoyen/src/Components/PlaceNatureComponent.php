<?php

declare(strict_types=1);

namespace App\Components;

use App\Form\PlaceNatureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('place_nature')]
class PlaceNatureComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(PlaceNatureType::class);
    }
}
