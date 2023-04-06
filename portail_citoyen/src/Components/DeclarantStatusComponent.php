<?php

declare(strict_types=1);

namespace App\Components;

use App\Form\Identity\DeclarantStatusType;
use App\Form\Model\Identity\DeclarantStatusModel;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('declarant_status')]
class DeclarantStatusComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public bool $fromSummary = false;

    public function __construct(private readonly SessionHandler $sessionHandler)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            DeclarantStatusType::class,
            $this->sessionHandler->getComplaint()?->getDeclarantStatus() ?? new DeclarantStatusModel()
        );
    }

    #[LiveAction]
    public function submit(#[LiveArg] bool $redirectToSummary = false): RedirectResponse
    {
        $this->submitForm();

        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();
        /** @var DeclarantStatusModel $declarantStatus */
        $declarantStatus = $this->getFormInstance()->getData();
        $complaint->setDeclarantStatus($declarantStatus);
        $this->sessionHandler->setComplaint($complaint);

        if (true === $redirectToSummary) {
            return $this->redirectToRoute('complaint_summary');
        }

        return $this->redirectToRoute('authentication');
    }
}
