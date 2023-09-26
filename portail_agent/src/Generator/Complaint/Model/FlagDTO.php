<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\Complaint;
use App\Referential\Entity\Unit;

class FlagDTO
{
    private ?string $flagTest;
    //    private string $flagBeginning;
    //    private string $flagEnd;
    //    private string $flagIp;
    private string $unitEmail;
    private string $unitDepartmentEmail;
    private string $unitCode;
    private string $unitDepartment;
    private string $unitName;
    private string $unitAddress;
    private string $unitPhone;
    private string $unitInstitution;
    //    private string $tcHome;
    //    private string $tcFacts;
    //    private ?string $unitCodeTcFacts;
    private string $declarationNumber;
    private string $createdAt;

    public function __construct(Complaint $complaint, Unit $unit)
    {
        $this->flagTest = $complaint->isTest() ? 'test' : '';
        //        $this->flagBeginning = $complaint->getStart()?->format('d/m/Y H:i:s') ?? '';
        //        $this->flagEnd = $complaint->getFinish()?->format('d/m/Y H:i:s') ?? '';
        //        $this->flagIp = $complaint->getDeclarantIp() ?? '';
        $this->unitEmail = $unit->getEmail();
        $this->unitDepartmentEmail = $unit->getHomeDepartmentEmail();
        $this->unitCode = $unit->getCode();
        $this->unitDepartment = $unit->getDepartment();
        $this->unitName = $unit->getName();
        $this->unitAddress = $unit->getAddress();
        $this->unitPhone = $unit->getPhone();
        $this->unitInstitution = $unit->getInstitutionCode()->value ?? '';
        //        $this->tcHome = strval($complaint->getTcHome()); // TODO : Still don't know what it is
        //        $this->tcFacts = strval($complaint->getTcFacts()); // TODO : Still don't know what it is
        //        $this->unitCodeTcFacts = strval($complaint->getUnitCodeTcFacts()); // TODO : Still don't know what it is
        $this->declarationNumber = $complaint->getDeclarationNumber();
        $this->createdAt = $complaint->getCreatedAt()?->format('d/m/Y H:i:s') ?? '';
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Flag' => [
            'Flag_Test' => $this->flagTest,
//            'Flag_Debut' => $this->flagBeginning,
//            'Flag_Fin' => $this->flagEnd,
//            'Flag_Ip' => $this->flagIp,
            'Mail_Unite' => $this->unitEmail,
            'Mail_Unite_Departement_Actif' => $this->unitDepartmentEmail,
            'Code_Unite' => $this->unitCode,
            'unite_dpt' => $this->unitDepartment,
            'unite_nom' => $this->unitName,
            'unite_adr' => $this->unitAddress,
            'unite_tph' => $this->unitPhone,
            'unite_institution' => $this->unitInstitution,
            'Numero_PEL' => $this->declarationNumber,
            'GDH_Validation_PEL' => $this->createdAt,
//            'TC_Domicile' => $this->tcHome,
//            'TC_Faits' => $this->tcFacts,
//            'Code_Unite_TC_Faits' => $this->unitCodeTcFacts,
        ]];
    }
}
