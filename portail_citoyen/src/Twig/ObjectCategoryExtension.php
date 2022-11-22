<?php

declare(strict_types=1);

namespace App\Twig;

use App\Thesaurus\ObjectCategoryThesaurusProviderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ObjectCategoryExtension extends AbstractExtension
{
    public function __construct(private readonly ObjectCategoryThesaurusProviderInterface $categoryThesaurusProvider)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('object_category_get_name', [$this, 'getName']),
        ];
    }

    /**
     * @throws \Exception
     */
    public function getName(?string $value): ?string
    {
        if (null === $value) {
            return null;
        }
        $choices = $this->categoryThesaurusProvider->getChoices();
        /** @var string|false $category */
        $category = array_search($value, $choices);

        if (false === $category) {
            throw new \Exception(sprintf('The %s value has not been found', $value));
        }

        return $category;
    }
}
