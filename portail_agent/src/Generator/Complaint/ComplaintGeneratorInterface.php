<?php

namespace App\Generator\Complaint;

use App\Entity\Complaint;
use App\Referential\Entity\Service;

interface ComplaintGeneratorInterface
{
    public function generate(Complaint $complaint, Service $service): mixed;
}
