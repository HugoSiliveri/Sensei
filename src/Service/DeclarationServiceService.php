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

    public function recupererParIdIntervenant(int $idIntervenant): array
    {
        return $this->declarationServiceRepository->recupererParIdIntervenant($idIntervenant);
    }

    /**
     * @param int $idIntervenant
     * @return array
     */
    public function recupererVueParIdIntervenant(int $idIntervenant): array
    {
        return $this->declarationServiceRepository->recupererVueParIdIntervenant($idIntervenant);
    }

    /**
     * @param int $idIntervenant
     * @param int $annee
     * @return array
     */
    public function recupererVueParIdIntervenantAnnuel(int $idIntervenant, int $annee): array
    {
        return $this->declarationServiceRepository->recupererVueParIdIntervenantAnnuel($idIntervenant, $annee);
    }

    /**
     * @param int $idUniteServiceAnnee
     * @return array
     */
    public function recupererParIdUSA(int $idUniteServiceAnnee): array
    {
        return $this->declarationServiceRepository->recupererParIdUSA($idUniteServiceAnnee);
    }
}