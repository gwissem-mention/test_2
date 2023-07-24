<?php

namespace App\Generator\Complaint;

use App\Entity\Complaint;
use App\Referential\Entity\Unit;

interface ComplaintGeneratorInterface
{
    public function generate(Complaint $complaint, Unit $unit): mixed;
}
