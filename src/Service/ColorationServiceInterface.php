<?php

namespace App\Sensei\Service;

interface ColorationServiceInterface
{
    public function recupererParIdUniteServiceAnnee(int $idUniteServiceAnnee): array;
}