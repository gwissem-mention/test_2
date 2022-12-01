<?php

declare(strict_types=1);

namespace App\Components\Objects;

use App\Form\Model\Objects\ObjectsModel;
use App\Form\Objects\ObjectsType;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent('objects')]
class ObjectsComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use LiveCollectionTrait;
    use DefaultActionTrait;

    public function __construct(private readonly SessionHandler $sessionHandler)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ObjectsType::class, $this->sessionHandler->getComplaint()?->getObjects() ?? new ObjectsModel());
    }

    #[LiveAction]
    public function submit(): void
    {
        $this->submitForm();

        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();
        /** @var ObjectsModel $objects */
        $objects = $this->getFormInstance()->getData();
        $this->sessionHandler->setComplaint($complaint->setObjects($objects));
    }
}
