<?php

declare(strict_types=1);

namespace App\Twig;

use App\Repository\UserRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UserExtension extends AbstractExtension
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('user_appellation', [$this, 'getAppellation']),
        ];
    }

    public function getAppellation(?int $id): ?string
    {
        if (is_null($id)) {
            return null;
        }

        return $this->userRepository->find($id)?->getAppellation();
    }
}
