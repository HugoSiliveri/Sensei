<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\StatutRepository;
use App\Sensei\Service\Exception\ServiceException;

class StatutService implements StatutServiceInterface
{
    public function __construct(
        private StatutRepository $statutRepository,
    )
    {
    }

    /**
     * @param int $idStatut
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idStatut): AbstractDataObject
    {
        if (!isset($idStatut)) {
            throw new ServiceException("L'identifiant n'est pas défini !");
        } else {
            $statut = $this->statutRepository->recupererParClePrimaire($idStatut);
            if (!isset($statut)) {
                throw new ServiceException("L'identifiant est inconnu !");
            } else {
                return $statut;
            }
        }
    }
}