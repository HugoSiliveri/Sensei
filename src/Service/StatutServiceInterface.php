<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface StatutServiceInterface
{
    /**
     * @param int $idStatut
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idStatut): AbstractDataObject;

    public function supprimerStatut(int $idStatut);

    public function recupererStatuts(): array;

    /**
     * @throws ServiceException
     */
    public function modifierStatut(array $statut);

    /**
     * @throws ServiceException
     */
    public function creerStatut(array $statut);
}