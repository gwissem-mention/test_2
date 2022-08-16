<?php

declare(strict_types=1);

namespace App\Thesaurus;

interface ThesaurusProviderInterface
{
    /**
     * @return mixed[]
     */
    public function getChoices(): array;
}
