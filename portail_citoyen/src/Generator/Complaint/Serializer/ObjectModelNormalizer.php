<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\Form\Model\Objects\ObjectModel;
use App\Thesaurus\ObjectCategoryThesaurusProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ObjectModelNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)] private readonly NormalizerInterface $normalizer,
        private readonly ObjectCategoryThesaurusProviderInterface $objectCategoryThesaurusProvider,
    ) {
    }

    /**
     * @param ObjectModel  $object
     * @param array<mixed> $context
     *
     * @return array<string, mixed>
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->normalizer->normalize($object, $format, $context);

        $data['status'] = [
            'code' => $data['status'],
            'label' => ObjectModel::STATUS_STOLEN === $object->getStatus() ? 'pel.stolen' : 'pel.degraded',
        ];

        $data['category'] = [
            'code' => $data['category'],
            'label' => array_search($object->getCategory(), $this->objectCategoryThesaurusProvider->getChoices(), true),
        ];

        return $data;
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return in_array('complaint_generator', $context) && $data instanceof ObjectModel;
    }
}
