<?php

namespace App\Sensei\Service;

use App\Sensei\Model\Repository\VoeuRepository;

class VoeuService implements VoeuServiceInterface
{
    public function __construct(
        private VoeuRepository $voeuRepository,
    )
    {
    }

    /**
     * @param int $idUniteServiceAnnee
     * @return array
     */
    public function recupererParIdUSA(int $idUniteServiceAnnee): array
    {
        return $this->voeuRepository->recupererParIdUSA($idUniteServiceAnnee);
    }

    /**
     * @param int $idIntervenant
     * @return array
     */
    public function recupererVueParIntervenant(int $idIntervenant): array
    {
        return $this->voeuRepository->recupererVueParIntervenant($idIntervenant);
    }
}