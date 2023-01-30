<?php

declare(strict_types=1);

namespace App\Twig;

use App\Referential\Repository\JobRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class JobExtension extends AbstractExtension
{
    public function __construct(private readonly JobRepository $jobRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('job_get_label', [$this, 'getLabel']),
        ];
    }

    public function getLabel(string $code): ?string
    {
        return $this->jobRepository->findOneBy(['code' => $code])?->getLabel();
    }
}
