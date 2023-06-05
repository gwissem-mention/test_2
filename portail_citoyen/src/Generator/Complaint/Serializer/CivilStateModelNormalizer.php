<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\AppEnum\Civility;
use App\Form\Model\Identity\CivilStateModel;
use App\Referential\Provider\Nationality\NationalityProviderInterface;
use App\Referential\Repository\JobRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Contracts\Translation\TranslatorInterface;

class CivilStateModelNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)] private readonly NormalizerInterface $normalizer,
        private readonly JobRepository $jobRepository,
        private readonly TranslatorInterface $translator,
        private readonly NationalityProviderInterface $nationalityProvider,
    ) {
    }

    /**
     * @param CivilStateModel $object
     * @param array<mixed>    $context
     *
     * @return array<string, mixed>
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->normalizer->normalize($object, $format, $context);
        $data['civility'] = [
            'code' => $data['civility'],
            'label' => $this->translator->trans((string) array_search($data['civility'], Civility::getChoices(), true)),
        ];

        /** @var string $dataNationality */
        $dataNationality = $data['nationality'];

        $nationality = $this->nationalityProvider->getByCode($dataNationality);

        $data['nationality'] = [
            'code' => $data['nationality'],
            'label' => $nationality->getLabel(),
        ];

        $job = $this->jobRepository->findOneBy(['code' => $data['job']]);

        $data['job'] = [
            'code' => $data['job'],
            'label' => $object->getCivility() === Civility::M->value ? $job?->getLabelMale() : $job?->getLabelFemale(),
        ];

        return $data;
    }

    /**
     * @param array<mixed> $context
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return in_array('complaint_generator', $context) && $data instanceof CivilStateModel;
    }
}
