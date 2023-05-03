<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\VoeuRepository;
use App\Sensei\Service\Exception\ServiceException;

class VoeuService implements VoeuServiceInterface
{
    public function __construct(
        private VoeuRepository $voeuRepository,
    )
    {
    }

    /**
     * @param int $idIntervenant
     * @return array
     * @throws ServiceException
     */
    public function recupererVueParIntervenant(int $idIntervenant): array
    {
        if (!isset($idIntervenant)) {
            throw new ServiceException("L'identifiant n'est pas défini !");
        } else {
            return $this->voeuRepository->recupererVueParIntervenant($idIntervenant);
        }
    }
}