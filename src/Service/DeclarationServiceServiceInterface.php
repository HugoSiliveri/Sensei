<?php

namespace App\Sensei\Service;

interface DeclarationServiceServiceInterface
{
    public function recupererParIdIntervenant(int $idIntervenant): array;
}