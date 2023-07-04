<?php

declare(strict_types=1);

namespace App\Complaint;

use App\Entity\Complaint;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\WorkflowInterface;

class ComplaintWorkflowManager
{
    public const TRANSITION_ASSIGN = 'assign';
    public const TRANSITION_REJECT = 'reject';
    public const TRANSITION_CLOSE_AFTER_SENDING_THE_REPORT = 'close_after_sending_the_report';
    public const TRANSITION_CLOSE_AFTER_APPOINTMENT = 'close_after_appointment';
    public const TRANSITION_SEND_TO_LRP = 'send_to_lrp';
    public const TRANSITION_SELF_ASSIGN = 'self_assign';
    public const TRANSITION_ASK_UNIT_REDIRECTION = 'ask_unit_redirection';
    public const TRANSITION_UNIT_REDIRECT = 'unit_redirect';
    public const TRANSITION_REJECT_UNIT_REDIRECTION = 'reject_unit_redirection';

    public function __construct(
        private readonly WorkflowInterface $complaintStateMachine
    ) {
    }

    /**
     * @throws ComplaintWorkflowException
     */
    public function assign(Complaint $complaint): Marking
    {
        if (!$this->complaintStateMachine->can($complaint, self::TRANSITION_ASSIGN)) {
            throw new ComplaintWorkflowException('This complaint cannot be assigned to user');
        }

        return $this->complaintStateMachine->apply($complaint, self::TRANSITION_ASSIGN);
    }

    /**
     * @throws ComplaintWorkflowException
     */
    public function selfAssign(Complaint $complaint): Marking
    {
        if (!$this->complaintStateMachine->can($complaint, self::TRANSITION_SELF_ASSIGN)) {
            throw new ComplaintWorkflowException('This complaint cannot be self assigned to user');
        }

        return $this->complaintStateMachine->apply($complaint, self::TRANSITION_SELF_ASSIGN);
    }

    /**
     * @throws ComplaintWorkflowException
     */
    public function reject(Complaint $complaint): Marking
    {
        if (!$this->complaintStateMachine->can($complaint, self::TRANSITION_REJECT)) {
            throw new ComplaintWorkflowException('This complaint cannot be rejected');
        }

        return $this->complaintStateMachine->apply($complaint, self::TRANSITION_REJECT);
    }

    /**
     * @throws ComplaintWorkflowException
     */
    public function closeAfterSendingTheReport(Complaint $complaint): Marking
    {
        if (!$this->complaintStateMachine->can($complaint, self::TRANSITION_CLOSE_AFTER_SENDING_THE_REPORT)) {
            throw new ComplaintWorkflowException('This complaint cannot be closed after sending the report');
        }

        return $this->complaintStateMachine->apply($complaint, self::TRANSITION_CLOSE_AFTER_SENDING_THE_REPORT);
    }

    /**
     * @throws ComplaintWorkflowException
     */
    public function closeAfterAppointment(Complaint $complaint): Marking
    {
        if (!$this->complaintStateMachine->can($complaint, self::TRANSITION_CLOSE_AFTER_APPOINTMENT)) {
            throw new ComplaintWorkflowException('This complaint cannot be closed after the appointment');
        }

        return $this->complaintStateMachine->apply($complaint, self::TRANSITION_CLOSE_AFTER_APPOINTMENT);
    }

    /**
     * @throws ComplaintWorkflowException
     */
    public function sendToLRP(Complaint $complaint): Marking
    {
        if (!$this->complaintStateMachine->can($complaint, self::TRANSITION_SEND_TO_LRP)) {
            throw new ComplaintWorkflowException('This complaint cannot be sent to LRP');
        }

        return $this->complaintStateMachine->apply($complaint, self::TRANSITION_SEND_TO_LRP);
    }

    /**
     * @throws ComplaintWorkflowException
     */
    public function askUnitRedirection(Complaint $complaint): Marking
    {
        if (!$this->complaintStateMachine->can($complaint, self::TRANSITION_ASK_UNIT_REDIRECTION)) {
            throw new ComplaintWorkflowException('This complaint cannot be asked to unit redirection');
        }

        return $this->complaintStateMachine->apply($complaint, self::TRANSITION_ASK_UNIT_REDIRECTION);
    }

    /**
     * @throws ComplaintWorkflowException
     */
    public function unitRedirect(Complaint $complaint): Marking
    {
        if (!$this->complaintStateMachine->can($complaint, self::TRANSITION_UNIT_REDIRECT)) {
            throw new ComplaintWorkflowException('This complaint cannot be redirected to unit');
        }

        return $this->complaintStateMachine->apply($complaint, self::TRANSITION_UNIT_REDIRECT);
    }

    /**
     * @throws ComplaintWorkflowException
     */
    public function rejectUnitRedirection(Complaint $complaint): Marking
    {
        if (!$this->complaintStateMachine->can($complaint, self::TRANSITION_REJECT_UNIT_REDIRECTION)) {
            throw new ComplaintWorkflowException('This redirection request cannot be rejected');
        }

        return $this->complaintStateMachine->apply($complaint, self::TRANSITION_REJECT_UNIT_REDIRECTION);
    }
}
