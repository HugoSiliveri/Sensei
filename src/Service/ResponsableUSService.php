<?php

namespace App\Sensei\Service;

use App\Sensei\Model\Repository\ResponsableUSRepository;
use App\Sensei\Service\Exception\ServiceException;

class ResponsableUSService implements ResponsableUSServiceInterface
{
    public function __construct(
        private readonly ResponsableUSRepository $responsableUSRepository,
    )
    {
    }

    /**
     * @param int $idIntervenant
     * @param int $millesime
     * @return array
     * @throws ServiceException
     */
    public function recupererParIdIntervenantAnnuel(int $idIntervenant, int $millesime): array
    {
        $responsableUS = $this->responsableUSRepository->recupererParIdIntervenantAnnuel($idIntervenant, $millesime);
        if (!isset($responsableUS)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $responsableUS;
        }
    }
}