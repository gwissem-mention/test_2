<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\Enum\DocumentType;
use App\Form\Model\Objects\ObjectModel;
use App\Thesaurus\ObjectCategoryThesaurusProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Contracts\Translation\TranslatorInterface;

class ObjectModelNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)] private readonly NormalizerInterface $normalizer,
        private readonly ObjectCategoryThesaurusProviderInterface $objectCategoryThesaurusProvider,
        private readonly TranslatorInterface $translator,
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
            'label' => $this->translator->trans(ObjectModel::STATUS_STOLEN === $object->getStatus() ? 'pel.stolen' : 'pel.degraded'),
        ];

        $data['category'] = [
            'code' => $data['category'],
            'label' => $this->translator->trans((string) array_search($object->getCategory(), $this->objectCategoryThesaurusProvider->getChoices(), true)),
        ];

        if ($documentTypeLabel = DocumentType::getLabel($object->getDocumentType())) {
            $data['documentType'] = $this->translator->trans($documentTypeLabel);
        }

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
