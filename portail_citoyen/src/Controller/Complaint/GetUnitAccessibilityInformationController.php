<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Serializer\UnitAccessibilityInformationNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class GetUnitAccessibilityInformationController extends AbstractController
{
    #[Route('/porter-plainte/informations-accessibilite/{unitIdAnonym}', name: 'complaint_unit_accessibility_information', methods: ['GET'])]
    public function __invoke(
        string $unitIdAnonym,
        HttpClientInterface $client,
        ParameterBagInterface $parameterBag,
        UnitAccessibilityInformationNormalizer $unitAccessibilityInformationNormalizer,
        TranslatorInterface $translator
    ): JsonResponse {
        /** @var string $accesLibreApiBaseUri */
        $accesLibreApiBaseUri = $parameterBag->get('app.acces_libre_api_base_uri');
        /** @var string|null $accesLibreApiKey */
        $accesLibreApiKey = $parameterBag->get('app.acces_libre_api_key');

        $client = $client->withOptions([
            'headers' => ['Authorization' => 'Api-Key '.$accesLibreApiKey],
        ]);

        $response = $client->request(
            'GET',
            $accesLibreApiBaseUri, [
                'query' => [
                    'source' => 'gendarmerie',
                    'source_id' => $unitIdAnonym,
                    'readable' => 'true',
                    'clean' => 'true',
                ],
            ]
        );

        if (0 === $response->toArray()['count']) {
            return $this->json([
                'content' => '<div><p>'.$translator->trans('pel.no.accessibility.information.found').'</p></div>',
                404,
            ]);
        }

        $information = $unitAccessibilityInformationNormalizer->normalize($response->toArray()['results'][0], null, ['unit_accessibility_information' => true]);

        return $this->json([
            'content' => $this->renderView(
                'common/_units_accessibility_information_content.html.twig',
                [
                    'name' => $information['name'],
                    'address' => $information['address'],
                    'phone' => $information['phone'],
                    'nearby_transports' => $information['transports'],
                    'nearby_parkings' => $information['parkings'],
                    'outer_paths' => $information['outer_paths'],
                    'entrances' => $information['entrances'],
                    'services' => $information['services'],
                    'toilets' => $information['toilets'],
                    'url' => $information['web_url'],
                ]
            ),
        ]);
    }
}
