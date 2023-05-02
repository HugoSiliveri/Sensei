<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\DepartementRepository;
use App\Sensei\Service\Exception\ServiceException;

class DepartementService implements DepartementServiceInterface
{
    public function __construct(
        private DepartementRepository $departementRepository,
    )
    {
    }

    /**
     * @param int $idDepartement
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idDepartement): AbstractDataObject
    {
        if (!isset($idDepartement)){
            throw new ServiceException("L'identifiant n'est pas défini !");
        } else {
            $departement = $this->departementRepository->recupererParClePrimaire($idDepartement);
            if (!isset($departement)){
                throw new ServiceException("L'identifiant est inconnu !");
            } else {
                return $departement;
            }
        }
    }
}