<?php

declare(strict_types=1);

namespace App\Components\Identity;

use App\Form\Identity\IdentityType;
use App\Form\Model\Identity\IdentityModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('identity')]
class IdentityComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    public function __construct(private readonly SessionHandler $sessionHandler)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            IdentityType::class,
            $this->sessionHandler->getComplaint()?->getIdentity() ?? new IdentityModel()
        );
    }

    #[LiveAction]
    public function submit(): RedirectResponse
    {
        $this->submitForm();

        return $this->redirectToRoute('facts');
    }
}
