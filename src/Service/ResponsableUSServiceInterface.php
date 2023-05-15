<?php

namespace App\Sensei\Service;

interface ResponsableUSServiceInterface
{
    /**
     * @param int $idIntervenant
     * @param int $millesime
     * @return array
     */
    public function recupererParIdIntervenantAnnuel(int $idIntervenant, int $millesime): array;
}