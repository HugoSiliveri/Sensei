<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\ResponsableUSRepository;
use App\Sensei\Service\Exception\ServiceException;

class ResponsableUSService implements ResponsableUSServiceInterface
{
    public function __construct(
        private ResponsableUSRepository $responsableUSRepository,
    )
    {
    }

    /**
     * @param int $idIntervenant
     * @return array
     * @throws ServiceException
     */
    public function recupererParIdIntervenantAnnuel(int $idIntervenant, int $millesime): array
    {
        if (!isset($idIntervenant) || !isset($millesime)) {
            throw new ServiceException("L'identifiant ou la date ne sont pas défini !");
        } else {
            $responsableUS = $this->responsableUSRepository->recupererParIdIntervenantAnnuel($idIntervenant, $millesime);
            if (!isset($responsableUS)) {
                throw new ServiceException("L'identifiant est inconnu !");
            } else {
                return $responsableUS;
            }
        }
    }
}