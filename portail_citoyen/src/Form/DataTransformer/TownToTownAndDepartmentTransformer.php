<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Contracts\Translation\TranslatorInterface;

class TownToTownAndDepartmentTransformer implements DataTransformerInterface
{
    private const DEPARTMENT_REGEX = '/\b([013-8]\d?|2[aAbB1-9]?|9[0-59]?|97[1-6])\b/';

    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    /**
     * @param array<int, mixed> $value
     */
    public function transform(mixed $value): string
    {
        if (false === (isset($value[0]) && isset($value[1]))) {
            throw new TransformationFailedException(sprintf('Cannot convert town %s to town and department', strval($value)));
        }

        return $this->translator->trans(strval($value[0])).' ('.$value[1].')';
    }

    /**
     * @param string $value
     */
    public function reverseTransform(mixed $value): string
    {
        if ('' === $value) {
            return '';
        }

        preg_match(self::DEPARTMENT_REGEX, $value, $matches);

        if (false === isset($matches[0])) {
            throw new TransformationFailedException(sprintf('Cannot extract town %s from town and department', $value));
        }

        return $matches[0];
    }
}
