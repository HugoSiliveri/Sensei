<?php

namespace App\Sensei\Service;

use App\Sensei\Model\Repository\ServiceAnnuelRepository;
use App\Sensei\Service\Exception\ServiceException;

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
}