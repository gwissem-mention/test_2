<?php

declare(strict_types=1);

namespace App\Twig;

use App\Thesaurus\JobThesaurusProviderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class JobExtension extends AbstractExtension
{
    public function __construct(private readonly JobThesaurusProviderInterface $jobThesaurusProvider)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('job_get_name', [$this, 'getName']),
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
        $choices = $this->jobThesaurusProvider->getChoices();
        /** @var string|false $job */
        $job = array_search($value, $choices);

        if (false === $job) {
            throw new \Exception(sprintf('The %s value has not been found', $value));
        }

        return $job;
    }
}
