<?php

declare(strict_types=1);

namespace App\Complaint;

use App\Session\ComplaintModel;

class FinalizeComplaint
{
    public function __construct(
        private readonly ComplaintModel $complaint,
    ) {
    }

    public function getComplaint(): ComplaintModel
    {
        return $this->complaint;
    }
}
