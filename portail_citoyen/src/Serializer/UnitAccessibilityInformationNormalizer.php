<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UnitAccessibilityInformationNormalizer implements NormalizerInterface
{
    /**
     * @param array<string, array<string, array<string, array<string, string>>>> $object
     * @param mixed[]                                                            $context
     *
     * @return array<string, array<string, array<string, array<string, string>>|string>>
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $data = [
            'name' => $object['nom'],
            'address' => $object['adresse'],
            'phone' => str_replace('+33 ', '0', $object['telephone']),
            'web_url' => $object['web_url'],
            'transport' => [],
            'parkings' => [],
            'outer_paths' => [],
            'entrances' => [],
            'services' => [],
            'toilets' => [],
        ];

        foreach ($object['accessibilite']['datas']['transport'] as $key => $value) {
            if (str_starts_with($key, 'transport_')) {
                $data['transports'][$key] = $value;
            }
            if (str_starts_with($key, 'stationnement_')) {
                $data['parkings'][$key] = $value;
            }
        }

        foreach ($object['accessibilite']['datas']['cheminement_ext'] as $key => $value) {
            if (str_starts_with($key, 'cheminement_ext_')) {
                $data['outer_paths'][$key] = $value;
            }
        }

        foreach ($object['accessibilite']['datas']['entree'] as $key => $value) {
            if (str_starts_with($key, 'entree_')) {
                $data['entrances'][$key] = $value;
            }
        }

        foreach ($object['accessibilite']['datas']['accueil'] as $key => $value) {
            if (str_starts_with($key, 'accueil_')) {
                $data['services'][$key] = $value;
            }
            if (str_starts_with($key, 'sanitaires_')) {
                $data['toilets'][$key] = $value;
            }
        }

        return $data;
    }

    /**
     * @param mixed[] $context
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return is_array($data) && in_array('unit_accessibility_information', $context);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => null,
        ];
    }
}
