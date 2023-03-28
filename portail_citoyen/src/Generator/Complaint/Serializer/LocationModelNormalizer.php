<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\Form\Model\LocationModel;
use App\Referential\Provider\Country\CountryProviderInterface;
use App\Referential\Repository\CityRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class LocationModelNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)] private readonly NormalizerInterface $normalizer,
        private readonly CountryProviderInterface $countryProvider,
        private readonly CityRepository $cityRepository,
    ) {
    }

    /**
     * @param LocationModel $object
     * @param array<mixed>  $context
     *
     * @return array<string, mixed>
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->normalizer->normalize($object, $format, $context);

        $country = $this->countryProvider->getByInseeCode(strval($data['country']));

        $data['country'] = [
            'inseeCode' => $data['country'],
            'label' => $country->getLabel(),
        ];

        $city = $this->cityRepository->findOneBy(['inseeCode' => $data['frenchTown']]);

        $data['frenchTown'] = [
            'inseeCode' => $data['frenchTown'],
            'label' => $city?->getLabel(),
        ];

        return $data;
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return in_array('complaint_generator', $context) && $data instanceof LocationModel;
    }
}
