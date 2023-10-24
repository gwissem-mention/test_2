<?php

declare(strict_types=1);

namespace App\Complaint;

use App\Entity\Comment;
use App\Entity\Complaint;
use App\Entity\User;
use App\Repository\RejectReasonRepository;
use Symfony\Bundle\SecurityBundle\Security;

class CommentHandler
{
    public function __construct(
        private readonly RejectReasonRepository $rejectReasonRepository,
        private readonly Security $security
    ) {
    }

    public function addRefusalReason(Complaint $complaint): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $refusalReason = $this->rejectReasonRepository->findOneBy(['code' => $complaint->getRefusalReason()]);
        $complaintRejectReasonComment = (new Comment())
            ->setAuthor($user)
            ->setTitle(Comment::COMPLAINT_REFUSAL_REASON)
            ->setSubtitle($refusalReason?->getLabel())
            ->setContent($complaint->getRefusalText() ?? '');

        $complaint->addComment($complaintRejectReasonComment);
    }
}
