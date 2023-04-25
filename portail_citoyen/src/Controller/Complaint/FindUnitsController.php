<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Referential\Repository\UnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: '/porter-plainte/unites/rechercher', name: 'complaint_find_units', methods: ['GET'])]
class FindUnitsController extends AbstractController
{
    public function __invoke(Request $request, UnitRepository $unitRepository, SerializerInterface $serializer): JsonResponse
    {
        if (!$request->query->has('lat') || !$request->query->has('lng')) {
            throw new BadRequestException('Missing lat or lng parameters');
        }

        $inseeCode = strval($request->get('insee_code'));

        $units = $unitRepository->findForMap(
            floatval($request->get('lat')),
            floatval($request->get('lng')),
            $inseeCode,
            preg_match("/^75\d{3}$/", $inseeCode) ? 10 : 5
        );

        return $this->json(
            ['units' => $units, 'view' => $this->renderView('common/_units_list.html.twig', ['units' => $units])],
            context: ['groups' => ['appointment_map']]
        );
    }
}
