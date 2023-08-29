<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Entity\User;
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
    #[Route('/mes-plaintes-x', name: 'my_complaints', methods: ['GET'])]
    #[Route('/plaintes-unite-x', name: 'my_complaints_unit', methods: ['GET'])]
    public function __invoke(Request $request, ComplaintRepository $complaintRepository): JsonResponse
    {
        $currentRoute = $request->attributes->get('_route');
        /** @var User $user */
        $user = $this->getUser();
        $columns = $request->query->all()['columns'] ?? [];
        $order = $request->query->all()['order'][0] ?? [];
        $search = $request->query->all()['search'] ?? [];
        $complaints = $complaintRepository->findAsPaginator(
            [[
                'field' => $columns[$order['column']]['data'],
                'dir' => $order['dir'],
            ]],
            $request->query->getInt('start'),
            'my_complaints_unit' === $currentRoute,
            $request->query->getInt('length'),
            $user->getServiceCode(), $user, $search['value']
        );
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
