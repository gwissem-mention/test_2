<?php

declare(strict_types=1);

namespace App\Tests\Behat\MockedClasses;

use App\Serializer\UnitAccessibilityInformationNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FakeGetUnitAccessibilityInformationController extends AbstractController
{
    #[Route('/porter-plainte/informations-accessibilite/{unitIdAnonym}', name: 'complaint_unit_accessibility_information', methods: ['GET'])]
    public function __invoke(
        string $unitIdAnonym,
        HttpClientInterface $client,
        ParameterBagInterface $parameterBag,
        UnitAccessibilityInformationNormalizer $unitAccessibilityInformationNormalizer,
        TranslatorInterface $translator
    ): JsonResponse {
        return $this->json([
            'content' => $this->renderView(
                'common/_units_accessibility_information_content.html.twig',
                [
                    'name' => 'Gendarmerie - Brigade de Voiron',
                    'address' => '2 Rue Danton 38500 Voiron',
                    'phone' => '04 76 05 01 83',
                    'nearby_transports' => [
                        'transport_station_presence' => "Arrêt de transport en commun à moins de 300 mètres de l'établissement",
                        'transport_information' => "Informations sur l'accessibilité par les transports en commun : Bus desservant rue Danton",
                    ],
                    'nearby_parkings' => [
                        'stationnement_presence' => "Des places de stationnement sont disponibles au sein de la parcelle de l'établissement",
                        'stationnement_pmr' => "Des places de stationnement adaptées sont disponibles au sein de la parcelle de l'établissement",
                        'stationnement_ext_presence' => "Des places de stationnement sont disponibles à moins de 200 mètres de l'établissement",
                        'stationnement_ext_pmr' => "Des places de stationnement adaptées sont disponibles à moins de 200 mètres de l'établissement",
                    ],
                    'outer_paths' => [
                        'cheminement_ext_presence' => "Pas de chemin extérieur entre le trottoir et l'entrée principale du bâtiment",
                    ],
                    'entrances' => [
                        'entree_reperage' => "L'entrée de l'établissement est facilement repérable",
                        'entree_porte_presence' => "Présence d'une porte à l'entrée de l'établissement",
                        'entree_porte_manoeuvre' => "Mode d'ouverture de la porte : Porte battante",
                        'entree_porte_type' => 'Type de porte : Manuelle',
                        'entree_vitree' => "La porte d'entrée est vitrée",
                        'entree_vitree_vitrophanie' => "Pas d'éléments contrastés permettant de visualiser les parties vitrées de l'entrée",
                        'entree_plain_pied' => "L'entrée se fait de plain-pied, c'est à dire sans rupture brutale de niveau",
                        'entree_dispositif_appel' => "Présence d'un dispositif comme une sonnette pour signaler sa présence",
                        'entree_dispositif_appel_type' => "Dispositifs d'appels présents : Interphone",
                        'entree_balise_sonore' => 'Pas de balise sonore facilitant son repérage par une personne aveugle ou malvoyante',
                        'entree_aide_humaine' => "Possibilité d'une aide humaine au déplacement",
                        'entree_largeur_mini' => "Largeur minimale de la porte d'entrée : 100",
                        'entree_pmr' => "Pas d'entrée secondaire spécifique dédiée aux personnes à mobilité réduite",
                    ],
                    'services' => [
                        'accueil_visibilite' => "La zone d'accueil (guichet d'accueil, caisse, secrétariat, etc) est visible depuis l'entrée du bâtiment",
                        'accueil_cheminement_plain_pied' => "L'accès à cet espace se fait de plain-pied, c'est à dire sans rupture brutale de niveau",
                        'accueil_retrecissement' => "Pas de rétrécissement inférieur à 90 centimètres du chemin pour atteindre la zone d'accueil",
                        'accueil_personnels' => "Personnel à l'accueil des personnes handicapées : Personnels sensibilisés ou formés",
                        'accueil_equipements_malentendants_presence' => 'Présence de produits ou prestations dédiés aux personnes sourdes ou malentendantes',
                        'accueil_equipements_malentendants' => 'Équipements ou prestations disponibles : langue des signes française (LSF)',
                    ],
                    'toilets' => [
                        'sanitaires_presence' => "Des sanitaires sont mis à disposition dans l'établissement",
                        'sanitaires_adaptes' => "Aucun sanitaire adapté mis à disposition dans l'établissement",
                    ],
                    'url' => 'https://acceslibre.beta.gouv.fr/app/38-voiron/a/gendarmerie/erp/gendarmerie-brigade-de-voiron/',
                ]
            ),
        ]);
    }
}
