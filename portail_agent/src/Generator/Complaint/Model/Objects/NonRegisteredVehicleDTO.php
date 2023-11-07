<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model\Objects;

use App\Entity\FactsObjects\AbstractObject;
use App\Entity\FactsObjects\Vehicle;

class NonRegisteredVehicleDTO extends AbstractObjectDTO
{
    private string $status;
    private string $label;

    public function __construct(Vehicle $object)
    {
        $this->status = AbstractObject::STATUS_STOLEN === $object->getStatus() ? 'Volé' : 'Dégradé';
        $this->label = $object->getLabel() ?? '';
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Objet_simple' => [
            'Objet_simple_Nature' => 'Véhicules non immatriculés',
            'Objet_simple_Description' => $this->label,
            'VL_Non_Immat_Statut' => $this->status,
            'VL_Non_Immat_Denomination' => $this->label,
        ]];
    }
}
