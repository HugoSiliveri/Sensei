<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\SaisonRepository;
use App\Sensei\Service\Exception\ServiceException;

class SaisonService implements SaisonServiceInterface
{
    public function __construct(
        private readonly SaisonRepository $saisonRepository
    )
    {
    }

    /**
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idSaison): AbstractDataObject
    {
        $intervenant = $this->saisonRepository->recupererParClePrimaire($idSaison);
        if (!isset($intervenant)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $intervenant;
        }
    }
}