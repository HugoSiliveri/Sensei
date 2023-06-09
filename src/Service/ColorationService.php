<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\ColorationRepository;
use App\Sensei\Service\Exception\ServiceException;

class ColorationService implements ColorationServiceInterface
{
    public function __construct(
        private readonly ColorationRepository $colorationRepository,
    )
    {
    }

    public function recupererParIdUniteServiceAnnee(int $idUniteServiceAnnee): array
    {
        return $this->colorationRepository->recupererParIdUniteServiceAnnee($idUniteServiceAnnee);
    }
}