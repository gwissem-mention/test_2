<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\Form\Model\Identity\CorporationModel;
use App\Referential\Provider\Country\CountryProviderInterface;
use App\Thesaurus\NationalityThesaurusProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Contracts\Translation\TranslatorInterface;

class CorporationModelNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)] private readonly NormalizerInterface $normalizer,
        private readonly CountryProviderInterface $countryProvider,
        private readonly NationalityThesaurusProviderInterface $nationalityThesaurusProvider,
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @param CorporationModel $object
     * @param array<mixed>     $context
     *
     * @return array<string, mixed>
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->normalizer->normalize($object, $format, array_merge($context, [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['sameAddress'],
        ]));

        $country = $this->countryProvider->getByInseeCode(strval($data['country']));

        $data['country'] = [
            'inseeCode' => $data['country'],
            'label' => $country->getLabel(),
        ];

        $data['nationality'] = [
            'code' => $data['nationality'],
            'label' => $this->translator->trans((string) array_search(intval($data['nationality']), $this->nationalityThesaurusProvider->getChoices(), true)),
        ];

        return $data;
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return in_array('complaint_generator', $context) && $data instanceof CorporationModel;
    }
}
