<?php

declare(strict_types=1);

namespace App\Twig;

use App\Thesaurus\NationalityThesaurusProviderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class NationalityExtension extends AbstractExtension
{
    public function __construct(private readonly NationalityThesaurusProviderInterface $nationalityThesaurusProvider)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('nationality_get_name', [$this, 'getName']),
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
        $choices = $this->nationalityThesaurusProvider->getChoices();
        /** @var string|false $nationality */
        $nationality = array_search($value, $choices);

        if (false === $nationality) {
            throw new \Exception(sprintf('The %s value has not been found', $value));
        }

        return $nationality;
    }
}
