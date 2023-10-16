<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\Form\Model\Facts\FactsModel;
use App\Referential\Entity\NaturePlace;
use App\Referential\Repository\NaturePlaceRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class FactsModelNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)] private readonly NormalizerInterface $normalizer,
        private readonly NaturePlaceRepository $naturePlaceRepository
    ) {
    }

    /**
     * @param FactsModel   $object
     * @param array<mixed> $context
     *
     * @return array<string, mixed>
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->normalizer->normalize($object, $format, $context);

        /** @var NaturePlace $naturePlace */
        $naturePlace = $this->naturePlaceRepository->find((int) ($object->getSubPlaceNature() ?? $object->getPlaceNature()));

        $data['placeNature'] = $naturePlace->getLabelThesaurus();

        // We only keep place nature thesaurus label
        unset($data['subPlaceNature']);

        return $data;
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return in_array('complaint_generator', $context) && $data instanceof FactsModel;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            FactsModel::class => true,
        ];
    }
}
