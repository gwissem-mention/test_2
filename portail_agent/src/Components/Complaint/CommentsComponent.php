<?php

declare(strict_types=1);

namespace App\Components\Complaint;

use App\Entity\Comment;
use App\Entity\Complaint;
use App\Form\Complaint\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
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

    #[LiveProp(writable: true)]
    private string $currentRoute;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

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

    public function getCurrentRoute(): string
    {
        return $this->currentRoute;
    }

    public function setCurrentRoute(string $currentRoute): void
    {
        $this->currentRoute = $currentRoute;
    }

    protected function instantiateForm(): FormInterface
    {
        $comment = new Comment();
        $comment
            ->setComplaint($this->complaint)
            ->setAuthor(Comment::AGENT_AUTHOR);

        return $this->createForm(
            CommentType::class,
            $comment,
            ['field_disabled' => $this->fieldDisabled]
        );
    }

    #[LiveAction]
    public function save(): RedirectResponse
    {
        $this->submitForm();
        /** @var Comment $comment */
        $comment = $this->getFormInstance()->getData();
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return new RedirectResponse(
            $this->urlGenerator->generate($this->currentRoute, [
                'id' => $comment->getComplaint()?->getId(),
                '_fragment' => 'comment-feed',
                'random' => md5((string) time()),   // Needed to really reload the page when the fragment is used
            ])
        );
    }
}
