<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ComplaintsController extends AbstractController
{
    /**
     * @throws \JsonException
     */
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/plaintes', name: 'home_complaints', methods: ['GET'])]
    public function __invoke(Request $request, ComplaintRepository $complaintRepository): JsonResponse
    {
        $columns = $request->query->all()['columns'] ?? [];
        $order = $request->query->all()['order'][0] ?? [];
        $complaints = $complaintRepository->findAsPaginator([['field' => $columns[$order['column']]['data'], 'dir' => $order['dir']]], $request->query->getInt('start'), $request->query->getInt('length'));

        $json = ['data' => []];
        foreach ($complaints as $complaint) {
            $json['data'][] = json_decode($this->renderView('pages/home/_complaint.json.twig', ['complaint' => $complaint]), true, 512, JSON_THROW_ON_ERROR);
        }

        $count = count($complaints);
        $json['recordsTotal'] = $count;
        $json['recordsFiltered'] = $count;

        return $this->json($json);
    }
}
