<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\Form\Model\Facts\FactsModel;
use App\Thesaurus\NaturePlaceThesaurusProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Contracts\Translation\TranslatorInterface;

class FactsModelNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)] private readonly NormalizerInterface $normalizer,
        private readonly NaturePlaceThesaurusProviderInterface $naturePlaceThesaurusProvider,
        private readonly TranslatorInterface $translator,
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

        $data['placeNature'] = [
            'code' => $data['placeNature'],
            'label' => $this->translator->trans((string) array_search($data['placeNature'], $this->naturePlaceThesaurusProvider->getChoices(), true)),
        ];

        return $data;
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return in_array('complaint_generator', $context) && $data instanceof FactsModel;
    }
}
