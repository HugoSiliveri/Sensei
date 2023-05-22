<?php

namespace App\Sensei\Service;

use App\Sensei\Model\Repository\DeclarationServiceRepository;

class DeclarationServiceService implements DeclarationServiceServiceInterface
{

    public function __construct(
        private readonly DeclarationServiceRepository $declarationServiceRepository
    )
    {
    }

    public function recupererParIdIntervenant(int $idIntervenant): array {
        return $this->declarationServiceRepository->recupererParIdIntervenant($idIntervenant);
    }

    /**
     * @param int $idIntervenant
     * @return array
     */
    public function recupererVueParIntervenant(int $idIntervenant): array
    {
        return $this->declarationServiceRepository->recupererVueParIntervenant($idIntervenant);
    }

}