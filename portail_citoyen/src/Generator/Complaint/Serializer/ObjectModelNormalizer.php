<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\AppEnum\MultimediaNature;
use App\AppEnum\RegisteredVehicleNature;
use App\Form\Model\Objects\ObjectModel;
use App\Referential\Entity\DocumentType;
use App\Referential\Provider\Country\CountryProviderInterface;
use App\Referential\Repository\DocumentTypeRepository;
use App\Referential\Repository\PaymentCategoryRepository;
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
        private readonly CountryProviderInterface $countryProvider,
        private readonly DocumentTypeRepository $documentTypeRepository,
        private readonly PaymentCategoryRepository $paymentCategoryRepository,
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

        if (null !== $object->getDocumentType()) {
            /** @var DocumentType $documentType */
            $documentType = $this->documentTypeRepository->find((int) $object->getDocumentType());
            $data['documentType'] = $documentType->getLabel();
        }

        if ($registeredVehicleNatureLabel = RegisteredVehicleNature::getLabel($object->getRegisteredVehicleNature())) {
            $data['registeredVehicleNature'] = $this->translator->trans($registeredVehicleNatureLabel);
        }

        if ($multimediaNatureLabel = MultimediaNature::getLabel($object->getMultimediaNature())) {
            $data['multimediaNature'] = $this->translator->trans($multimediaNatureLabel);
        }

        if (null != $data['documentIssuingCountry']) {
            /** @var int $countryCode */
            $countryCode = $data['documentIssuingCountry'];
            $documentIssuingCountry = $this->countryProvider->getByInseeCode(strval($countryCode));

            $data['documentIssuingCountry'] = [
                'inseeCode' => $countryCode,
                'label' => $documentIssuingCountry->getLabel(),
            ];
        }

        if (null !== $object->getPaymentCategory()) {
            $data['paymentCategory'] = $this->paymentCategoryRepository->findOneBy(['code' => $object->getPaymentCategory()])?->getLabel();
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
