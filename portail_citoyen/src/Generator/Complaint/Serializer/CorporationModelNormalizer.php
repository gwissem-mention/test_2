<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\Form\Model\Identity\CorporationModel;
use App\Referential\Provider\Country\CountryProviderInterface;
use App\Referential\Provider\Nationality\NationalityProviderInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CorporationModelNormalizer implements NormalizerInterface
{
    public function __construct(
        private readonly NormalizerInterface $normalizer,
        private readonly CountryProviderInterface $countryProvider,
        private readonly NationalityProviderInterface $nationalityProvider,
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
        /** @var array<string, string|int> $data */
        $data = $this->normalizer->normalize($object, $format, array_merge($context, [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['sameAddress'],
        ]));

        $country = $this->countryProvider->getByInseeCode(strval($data['country']));

        $data['country'] = [
            'inseeCode' => $data['country'],
            'label' => $country->getLabel(),
        ];

        /** @var string $dataNationality */
        $dataNationality = $data['nationality'];
        $nationality = $this->nationalityProvider->getByCode($dataNationality);

        $data['nationality'] = [
            'code' => $data['nationality'],
            'label' => $nationality->getLabel(),
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

    /**
     * @return array<string, mixed>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            CorporationModel::class => true,
        ];
    }
}
