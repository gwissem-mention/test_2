<?php

declare(strict_types=1);

namespace App\Components\Complaint;

use App\Form\Complaint\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('comment_form')]
class CommentFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(writable: true)]
    private bool $fieldDisabled;

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
