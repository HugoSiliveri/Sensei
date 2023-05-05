<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;

interface ResponsableUSServiceInterface
{
    /**
     * @param int $idIntervenant
     * @param int $millesime
     * @return array
     */
    public function recupererParIdIntervenantAnnuel(int $idIntervenant, int $millesime): array;
}