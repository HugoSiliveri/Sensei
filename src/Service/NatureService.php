<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\NatureRepository;
use App\Sensei\Service\Exception\ServiceException;

class NatureService implements NatureServiceInterface
{
    public function __construct(
        private NatureRepository $natureRepository,
    )
    {
    }

    /**
     * @param int $idNature
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idNature): AbstractDataObject
    {
        $nature = $this->natureRepository->recupererParClePrimaire($idNature);
        if (!isset($nature)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $nature;
        }
    }
}