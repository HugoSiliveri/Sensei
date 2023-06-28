<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\SemestreRepository;
use App\Sensei\Service\Exception\ServiceException;

class SemestreService implements SemestreServiceInterface
{
    public function __construct(
        private readonly SemestreRepository $semestreRepository
    )
    {
    }

    /**
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idSemestre): AbstractDataObject
    {
        $intervenant = $this->semestreRepository->recupererParClePrimaire($idSemestre);
        if (!isset($intervenant)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $intervenant;
        }
    }
}