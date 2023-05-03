<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\UniteServiceAnneeRepository;
use App\Sensei\Service\Exception\ServiceException;

class UniteServiceAnneeService implements UniteServiceAnneeServiceInterface
{
    public function __construct(
        private UniteServiceAnneeRepository $uniteServiceAnneeRepository,
    )
    {
    }

    /**
     * @param int $idUniteServiceAnnee
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idUniteServiceAnnee): AbstractDataObject
    {
        if (!isset($idUniteServiceAnnee)) {
            throw new ServiceException("L'identifiant n'est pas défini !");
        } else {
            $uniteServiceAnnee = $this->uniteServiceAnneeRepository->recupererParClePrimaire($idUniteServiceAnnee);
            if (!isset($uniteServiceAnnee)) {
                throw new ServiceException("L'identifiant est inconnu !");
            } else {
                return $uniteServiceAnnee;
            }
        }
    }
}