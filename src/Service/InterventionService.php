<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\InterventionRepository;
use App\Sensei\Service\Exception\ServiceException;

class InterventionService implements InterventionServiceInterface
{
    public function __construct(
        private InterventionRepository $interventionRepository,
    )
    {
    }

    /**
     * @param int $idIntervention
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idIntervention): AbstractDataObject
    {
        $intervention = $this->interventionRepository->recupererParClePrimaire($idIntervention);
        if (!isset($intervention)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $intervention;
        }
    }
}