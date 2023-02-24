<?php

declare(strict_types=1);

namespace App\Twig;

use App\Enum\Civility;
use App\Form\Model\Identity\CivilStateModel;
use App\Referential\Repository\JobRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CivilStateExtension extends AbstractExtension
{
    public function __construct(private readonly JobRepository $jobRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('job_label', [$this, 'getJobLabel']),
        ];
    }

    public function getJobLabel(CivilStateModel $civilStateModel): ?string
    {
        $job = $this->jobRepository->findOneBy(['code' => $civilStateModel->getJob()]);

        return $civilStateModel->getCivility() === Civility::M->value ? $job?->getLabelMale() : $job?->getLabelFemale();
    }
}
