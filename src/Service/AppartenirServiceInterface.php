<?php

namespace App\Sensei\Service;

use App\Sensei\Service\Exception\ServiceException;

interface AppartenirServiceInterface
{
    public function creerAppartenir(array $appartenir);

    /**
     * @throws ServiceException
     */
    public function recupererParIdUniteService(int $idUniteService): array;

    public function verifierAppartenance(int $idUniteService, int $idDepartement): bool;

}