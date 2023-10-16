<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DateTimeNormalizer implements NormalizerInterface
{
    /**
     * @param \DateTimeInterface $object
     * @param array<mixed>       $context
     *
     * @return array<string, mixed>
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'date' => $object->format('c'),
            'timestamp' => $object->getTimestamp(),
            'timezone' => $object->getTimezone()->getName(),
        ];
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return in_array('complaint_generator', $context) && $data instanceof \DateTimeInterface;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            \DateTimeInterface::class => true,
        ];
    }
}
