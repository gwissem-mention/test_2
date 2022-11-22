<?php

declare(strict_types=1);

namespace App\Components\Facts;

use App\Form\Facts\FactsType;
use App\Form\Model\Facts\FactsModel;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent('facts')]
class FactsComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use LiveCollectionTrait;
    use DefaultActionTrait;

    public function __construct(private readonly SessionHandler $sessionHandler)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(FactsType::class, $this->sessionHandler->getComplaint()?->getFacts() ?? new FactsModel());
    }

    #[LiveAction]
    public function submit(): Response
    {
        $this->submitForm();

        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();
        /** @var FactsModel $facts */
        $facts = $this->getFormInstance()->getData();
        $this->sessionHandler->setComplaint($complaint->setFacts($facts));

        return $this->redirectToRoute('summary');
    }
}
