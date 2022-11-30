<?php

declare(strict_types=1);

namespace App\Components\AdditionalInformation;

use App\Form\AdditionalInformation\AdditionalInformationType;
use App\Form\Model\AdditionalInformation\AdditionalInformationModel;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('additional_information')]
class AdditionalInformationComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    public function __construct(private readonly SessionHandler $sessionHandler)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            AdditionalInformationType::class,
            $this->sessionHandler->getComplaint()?->getAdditionalInformation() ?? new AdditionalInformationModel()
        );
    }

    #[LiveAction]
    public function submit(): RedirectResponse
    {
        $this->submitForm();

        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();
        /** @var AdditionalInformationModel $additionalInformation */
        $additionalInformation = $this->getFormInstance()->getData();
        $complaint->setAdditionalInformation($additionalInformation);
        $this->sessionHandler->setComplaint($complaint);

        return $this->redirectToRoute('summary');
    }
}
