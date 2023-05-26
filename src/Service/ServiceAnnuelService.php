<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\ServiceAnnuelRepository;

class ServiceAnnuelService implements ServiceAnnuelServiceInterface
{
    public function __construct(
        private readonly ServiceAnnuelRepository $serviceAnnuelRepository,
    )
    {
    }

    /**
     * @param int $idIntervenant
     * @return array
     */
    public function recupererParIntervenant(int $idIntervenant): array
    {
        return $this->serviceAnnuelRepository->recupererParIntervenant($idIntervenant);
    }

    /**
     * @param int $idIntervenant
     * @param int $millesime
     * @return AbstractDataObject|null
     */
    public function recupererParIntervenantAnnuel(int $idIntervenant, int $millesime): ?AbstractDataObject
    {

        return $this->serviceAnnuelRepository->recupererParIntervenantAnnuel($idIntervenant, $millesime);
    }

    public function recupererParIntervenantAnnuelPlusRecent(int $idIntervenant): ?AbstractDataObject
    {
        return $this->serviceAnnuelRepository->recupererParIntervenantAnnuelPlusRecent($idIntervenant);
    }
}