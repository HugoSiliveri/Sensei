<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\EmploiRepository;
use App\Sensei\Service\Exception\ServiceException;

class EmploiService implements EmploiServiceInterface
{
    public function __construct(
        private EmploiRepository $emploiRepository,
    )
    {
    }

    /**
     * @param int $idEmploi
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idEmploi): AbstractDataObject
    {
        if (!isset($idEmploi)) {
            throw new ServiceException("L'identifiant n'est pas défini !");
        } else {
            $emploi = $this->emploiRepository->recupererParClePrimaire($idEmploi);
            if (!isset($emploi)) {
                throw new ServiceException("L'identifiant est inconnu !");
            } else {
                return $emploi;
            }
        }
    }
}