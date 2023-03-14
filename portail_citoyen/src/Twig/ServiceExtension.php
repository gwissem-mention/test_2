<?php

declare(strict_types=1);

namespace App\Twig;

use App\Referential\Entity\Service;
use App\Referential\Repository\ServiceRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ServiceExtension extends AbstractExtension
{
    public function __construct(private readonly ServiceRepository $serviceRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('service', [$this, 'getService']),
        ];
    }

    public function getService(?string $code): ?Service
    {
        if (is_null($code)) {
            return null;
        }

        return $this->serviceRepository->findOneBy(['code' => $code]);
    }
}
