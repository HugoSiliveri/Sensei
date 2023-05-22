<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\ColorationRepository;
use App\Sensei\Service\Exception\ServiceException;

class ColorationService implements ColorationServiceInterface
{
    public function __construct(
        private ColorationRepository $colorationRepository,
    )
    {
    }

    /**
     * @param int $idColoration
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idColoration): AbstractDataObject
    {
        $coloration = $this->colorationRepository->recupererParClePrimaire($idColoration);
        if (!isset($coloration)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $coloration;
        }
    }

    public function recupererParIdUniteServiceAnnee(int $idUniteServiceAnnee): array
    {
        return $this->colorationRepository->recupererParIdUniteServiceAnnee($idUniteServiceAnnee);
    }
}