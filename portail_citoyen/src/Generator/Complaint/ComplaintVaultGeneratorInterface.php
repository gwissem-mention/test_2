<?php

declare(strict_types=1);

namespace App\Generator\Complaint;

use App\Session\ComplaintModel;

interface ComplaintVaultGeneratorInterface
{
    public function generate(ComplaintModel $complaint): mixed;
}
