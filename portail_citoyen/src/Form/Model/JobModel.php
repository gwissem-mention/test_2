<?php

declare(strict_types=1);

namespace App\Form\Model;

class JobModel
{
    private int $job;

    public function getJob(): int
    {
        return $this->job;
    }

    public function setJob(int $job): void
    {
        $this->job = $job;
    }
}
