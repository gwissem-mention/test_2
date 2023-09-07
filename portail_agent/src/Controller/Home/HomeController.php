<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Form\Complaint\BulkAssignType;
use App\Form\Complaint\BulkReassignType;
use App\Form\Complaint\SearchType;
use App\Form\DTO\BulkAssignAction;
use App\Form\DTO\BulkReassignAction;
use App\Form\RightDelegationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/', name: 'home', methods: ['GET'])]
#[Route('/mes-plaintes', name: 'home_complaints', methods: ['GET'])]
class HomeController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    public function __invoke(Request $request): Response
    {
        $currentRoute = $request->attributes->get('_route');
        $pathComplaints = 'home_complaints' === $currentRoute ? 'my_complaints' : 'my_complaints_unit';

        $bulkAssignForm = $this->createForm(BulkAssignType::class, new BulkAssignAction());
        $bulkReassignForm = $this->createForm(BulkReassignType::class, new BulkReassignAction());

        return $this->render('pages/home/index.html.twig', [
            'search_form' => $this->createForm(SearchType::class),
            'bulk_assign_form' => $bulkAssignForm->createView(),
            'bulk_reassign_form' => $bulkReassignForm->createView(),
            'pathComplaints' => $pathComplaints,
            'delegation_right_form' => $this->createForm(RightDelegationType::class),
        ]);
    }
}
