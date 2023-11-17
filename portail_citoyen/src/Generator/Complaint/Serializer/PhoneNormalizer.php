<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\Form\Model\Identity\PhoneModel;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PhoneNormalizer implements NormalizerInterface
{
    /**
     * @param PhoneModel   $object
     * @param array<mixed> $context
     *
     * @return array<string, string|null>|null
     */
    public function normalize(mixed $object, string $format = null, array $context = []): ?array
    {
        if (null !== $object->getNumber()) {
            return [
                'country' => $object->getCountry(),
                'code' => $object->getCode(),
                'number' => $object->getNumber(),
            ];
        }

        return null;
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return in_array('complaint_generator', $context) && $data instanceof PhoneModel;
    }

    /**
     * @return array<string, bool>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            PhoneModel::class => true,
        ];
    }
}
