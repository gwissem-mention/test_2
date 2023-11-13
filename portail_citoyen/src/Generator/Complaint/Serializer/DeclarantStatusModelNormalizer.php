<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\AppEnum\DeclarantStatus;
use App\Form\Model\Identity\IdentityModel;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DeclarantStatusModelNormalizer implements NormalizerInterface
{
    public function __construct(
        private readonly NormalizerInterface $normalizer,
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @param IdentityModel $object
     * @param array<mixed>  $context
     *
     * @return array<string, mixed>
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->normalizer->normalize($object, $format, $context);

        $data['declarantStatus'] = [
            'code' => $data['declarantStatus'],
            'label' => $this->translator->trans((string) array_search($data['declarantStatus'], DeclarantStatus::getChoices(), true)),
        ];

        return $data;
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return in_array('complaint_generator', $context) && $data instanceof IdentityModel;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            IdentityModel::class => true,
        ];
    }
}
