<?php

declare(strict_types=1);

namespace App\Twig;

use App\Referential\Repository\PaymentCategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PaymentCategoryExtension extends AbstractExtension
{
    public function __construct(private readonly PaymentCategoryRepository $paymentCategoryRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('payment_category_label', [$this, 'getLabel']),
        ];
    }

    public function getLabel(string $value = null): ?string
    {
        return $this->paymentCategoryRepository->findOneBy(['code' => $value])?->getLabel();
    }
}
