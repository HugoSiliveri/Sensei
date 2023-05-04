<?php

namespace App\Sensei\Service;

use App\Sensei\Service\Exception\ServiceException;

interface VoeuServiceInterface
{
    /**
     * @param int $idIntervenant
     * @return array
     * @throws ServiceException
     */
    public function recupererVueParIntervenant(int $idIntervenant): array;

    public function creerUnCSV(int $idIntervenant, string $nomFichier);
}