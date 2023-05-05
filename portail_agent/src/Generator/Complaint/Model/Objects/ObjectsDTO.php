<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model\Objects;

use App\Entity\Complaint;
use App\Entity\FactsObjects\AbstractObject;
use Doctrine\Common\Collections\Collection;

class ObjectsDTO
{
    //    private ?string $prejudiceAssess;
    private ?string $prejudiceEstimation;
    //    private ?string $miscellaneousObject;

    public function __construct(Complaint $complaint)
    {
        //        $this->prejudiceAssess = '1'; // TODO: Still don't know what it is
        $this->prejudiceEstimation = $this->getPrejudiceEstimation($complaint->getObjects());
        //        $this->miscellaneousObject = null; // TODO: Map this property when we know the data
    }

    /**
     * @return array<string, string|null>
     */
    public function getArray(): array
    {
        return [
//            'Objets_Prejudice_Evaluer' => $this->prejudiceAssess,
            'Objets_Prejudice_Estimation' => $this->prejudiceEstimation,
//            'Objet_Divers' => $this->miscellaneousObject,
        ];
    }

    /**
     * @param Collection<int, AbstractObject> $objects
     */
    private function getPrejudiceEstimation(Collection $objects): string
    {
        $estimation = 0;
        foreach ($objects as $object) {
            $estimation += $object->getAmount();
        }

        return (string) $estimation;
    }
}
