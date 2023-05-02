<?php

namespace App\Sensei\Service;

use App\Sensei\Service\Exception\ServiceException;

interface ServiceAnnuelServiceInterface
{
    /**
     * @param int $idIntervenant
     * @return array
     * @throws ServiceException
     */
    public function recupererParIntervenant(int $idIntervenant): array;
}