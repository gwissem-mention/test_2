<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Entity\FactsObjects\AdministrativeDocument;
use App\Entity\FactsObjects\MultimediaObject;
use App\Entity\FactsObjects\PaymentMethod;
use App\Entity\FactsObjects\SimpleObject;
use App\Entity\FactsObjects\Vehicle;
use App\Form\Complaint\AssignType;
use App\Form\Complaint\FactsObjects\AdministrativeDocumentType;
use App\Form\Complaint\FactsObjects\MultimediaObjectType;
use App\Form\Complaint\FactsObjects\PaymentMethodType;
use App\Form\Complaint\FactsObjects\SimpleObjectType;
use App\Form\Complaint\FactsObjects\VehicleType;
use App\Form\Complaint\RejectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ObjectController extends AbstractController
{
    #[Route(path: '/plainte/objets/{id}', name: 'complaint_objects', methods: ['GET'])]
    public function __invoke(Complaint $complaint): Response
    {
        $this->denyAccessUnlessGranted('COMPLAINT_VIEW', $complaint);

        $objectForms = [];
        foreach ($complaint->getObjects() as $object) {
            switch (true) {
                case $object instanceof AdministrativeDocument:
                    $objectForms[] = $this->createForm(AdministrativeDocumentType::class, $object)->createView();
                    break;
                case $object instanceof MultimediaObject:
                    $objectForms[] = $this->createForm(MultimediaObjectType::class, $object)->createView();
                    break;
                case $object instanceof PaymentMethod:
                    $objectForms[] = $this->createForm(PaymentMethodType::class, $object)->createView();
                    break;
                case $object instanceof SimpleObject:
                    $objectForms[] = $this->createForm(SimpleObjectType::class, $object)->createView();
                    break;
                case $object instanceof Vehicle:
                    $objectForms[] = $this->createForm(VehicleType::class, $object)->createView();
                    break;
            }
        }

        return $this->render('pages/complaint/objects.html.twig', [
            'complaint' => $complaint,
            'reject_form' => $this->createForm(RejectType::class, $complaint),
            'object_forms' => $objectForms,
            'assign_form' => $this->createForm(AssignType::class, $complaint),
        ]);
    }
}
