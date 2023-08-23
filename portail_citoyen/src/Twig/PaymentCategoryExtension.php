<?php

declare(strict_types=1);

namespace App\Twig;

use App\AppEnum\PaymentCategory;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PaymentCategoryExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('payment_category_label', [$this, 'getLabel']),
        ];
    }

    public function getLabel(int $value = null): ?string
    {
        return PaymentCategory::getLabel($value);
    }
}
