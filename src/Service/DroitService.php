<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\DroitRepository;
use App\Sensei\Service\Exception\ServiceException;

class DroitService implements DroitServiceInterface
{

    public function __construct(
        private DroitRepository $droitRepository,
    )
    {
    }

    /**
     * @param int $idDroit
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idDroit): AbstractDataObject
    {
        if (!isset($idDroit)){
            throw new ServiceException("L'identifiant n'est pas défini !");
        } else {
            $droit = $this->droitRepository->recupererParClePrimaire($idDroit);
            if (!isset($droit)){
                throw new ServiceException("L'identifiant est inconnu !");
            } else {
                return $droit;
            }
        }
    }
}