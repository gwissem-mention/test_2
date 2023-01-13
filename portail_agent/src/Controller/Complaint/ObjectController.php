<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Form\Complaint\AssignType;
use App\Form\Complaint\FactsObjectType;
use App\Form\Complaint\RejectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ObjectController extends AbstractController
{
    #[Route(path: '/plainte/objets/{id}', name: 'complaint_objects', methods: ['GET'])]
    public function __invoke(Complaint $complaint): Response
    {
        $objectForms = [];
        foreach ($complaint->getObjects() as $object) {
            $objectForms[] = $this->createForm(FactsObjectType::class, $object)->createView();
        }

        return $this->render('pages/complaint/objects.html.twig', [
            'complaint' => $complaint,
            'reject_form' => $this->createForm(RejectType::class, $complaint),
            'object_forms' => $objectForms,
            'assign_form' => $this->createForm(AssignType::class, $complaint),
        ]);
    }
}
