<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Serializer;

use App\Enum\Civility;
use App\Form\Model\Identity\CivilStateModel;
use App\Referential\Repository\JobRepository;
use App\Thesaurus\NationalityThesaurusProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Contracts\Translation\TranslatorInterface;

class CivilStateModelNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)] private readonly NormalizerInterface $normalizer,
        private readonly NationalityThesaurusProviderInterface $nationalityThesaurusProvider,
        private readonly JobRepository $jobRepository,
        private readonly TranslatorInterface $translator,
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
            'label' => array_search($data['civility'], Civility::getChoices(), true),
        ];

        /** @var string $nationalityLabel */
        $nationalityLabel = array_search($data['nationality'], $this->nationalityThesaurusProvider->getChoices(), true);
        $data['nationality'] = [
            'code' => $data['nationality'],
            'label' => $this->translator->trans($nationalityLabel),
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
