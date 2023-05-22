<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\Form\Model\Identity\ContactInformationModel;
use App\Referential\Provider\Country\CountryProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ContactInformationModelNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)] private readonly NormalizerInterface $normalizer,
        private readonly CountryProviderInterface $countryProvider,
    ) {
    }

    /**
     * @param ContactInformationModel $object
     * @param array<mixed>            $context
     *
     * @return array<string, mixed>
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array<string, string|int> $data */
        $data = $this->normalizer->normalize($object, $format, array_merge($context, [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['etalabInput', 'sameAddress'],
        ]));

        $country = $this->countryProvider->getByInseeCode(strval($data['country']));

        $data['country'] = [
            'inseeCode' => $data['country'],
            'label' => $country->getLabel(),
        ];

        return $data;
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return in_array('complaint_generator', $context) && $data instanceof ContactInformationModel;
    }
}
