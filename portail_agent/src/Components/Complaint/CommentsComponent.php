<?php

declare(strict_types=1);

namespace App\Components\Complaint;

use App\Entity\Complaint;
use App\Form\Complaint\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('comments')]
class CommentsComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(writable: true)]
    private Complaint $complaint;

    #[LiveProp(writable: true)]
    private bool $fieldDisabled;

    public function getComplaint(): Complaint
    {
        return $this->complaint;
    }

    public function setComplaint(Complaint $complaint): void
    {
        $this->complaint = $complaint;
    }

    public function getFieldDisabled(): bool
    {
        return $this->fieldDisabled;
    }

    public function setFieldDisabled(bool $fieldDisabled): void
    {
        $this->fieldDisabled = $fieldDisabled;
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CommentType::class, null, ['field_disabled' => $this->fieldDisabled]);
    }
}
