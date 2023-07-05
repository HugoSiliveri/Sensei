<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface NatureServiceInterface
{
    /**
     * @param int $idNature
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idNature): AbstractDataObject;

    /**
     * @throws ServiceException
     */
    public function modifierNature(array $nature);

    /**
     * @throws ServiceException
     */
    public function creerNature(array $nature);

    public function supprimerNature(int $idNature);

    public function recupererNatures(): array;
}