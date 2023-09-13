<?php

declare(strict_types=1);

namespace App\Components;

use App\Form\LawRefresherType;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('law_refresher')]
class LawRefresherComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    public function __construct(private readonly SessionHandler $sessionHandler)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            LawRefresherType::class,
            $this->sessionHandler->getComplaint()
        );
    }

    #[LiveAction]
    public function submit(): RedirectResponse
    {
        $this->submitForm();

        /** @var ComplaintModel $complaint */
        $complaint = $this->getFormInstance()->getData();

        $this->sessionHandler->setComplaint($complaint);

        return $this->redirectToRoute('complaint_identity');
    }
}
