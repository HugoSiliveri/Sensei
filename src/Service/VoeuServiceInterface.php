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

    public function recupererParIdUSA(int $idUniteServiceAnnee): array;

    public function recupererVueParIntervenantAnnuel(int $idIntervenant, int $annee): array;

    public function creerVoeu(array $voeuTab);

    public function recupererParIntervenantAnnuel(int $idIntervenant, int $annee): array;
}