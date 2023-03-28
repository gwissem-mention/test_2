<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model\Objects;

use App\Entity\FactsObjects\Vehicle;

class VehicleDTO extends AbstractObjectDTO
{
    private string $registrationNumber;
    private string $model;
    private string $brand;
    private string $insuranceCompany;
    private string $insuranceNumber;

    public function __construct(Vehicle $vehicle)
    {
        $this->registrationNumber = $vehicle->getRegistrationNumber() ?? '';
        $this->model = $vehicle->getModel() ?? '';
        $this->brand = $vehicle->getBrand() ?? '';
        $this->insuranceCompany = $vehicle->getInsuranceCompany() ?? '';
        $this->insuranceNumber = $vehicle->getInsuranceNumber() ?? '';
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['VL' => [
            'VL_Nmr_Immatriculation' => $this->registrationNumber,
            'VL_Type_Commercial' => $this->model,
            'VL_Marque' => $this->brand,
            'VL_Assurance_Nom' => $this->insuranceCompany,
            'VL_Assurance_Police' => $this->insuranceNumber,
        ]];
    }
}
