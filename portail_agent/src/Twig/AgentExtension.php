<?php

declare(strict_types=1);

namespace App\Twig;

use App\Referential\Repository\AgentRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AgentExtension extends AbstractExtension
{
    public function __construct(private readonly AgentRepository $agentRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('agent_name', [$this, 'getName']),
        ];
    }

    public function getName(?int $id): ?string
    {
        if (null === $id) {
            return null;
        }

        return $this->agentRepository->find($id)?->getName();
    }
}
