<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\Form\Model\AdditionalInformation\AdditionalInformationModel;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdditionalInformationModelNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)] private readonly NormalizerInterface $normalizer,
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @param AdditionalInformationModel $object
     * @param array<mixed>               $context
     *
     * @return array<string, mixed>
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->normalizer->normalize($object, $format, $context);

        $data['cctvPresent'] = [
            'code' => $data['cctvPresent'],
            'label' => $this->translator->trans((string) array_search($data['cctvPresent'], AdditionalInformationModel::getCctvPresentChoices(), true)),
        ];

        return $data;
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return in_array('complaint_generator', $context) && $data instanceof AdditionalInformationModel;
    }
}
