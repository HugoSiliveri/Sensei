<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface ComposanteServiceInterface
{
    /**
     * @param int $idComposante
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idComposante): AbstractDataObject;

    public function creerComposante(array $composante);

    public function recupererComposantes();

    public function supprimerComposante(int $idComposante);

    /**
     * @throws ServiceException
     */
    public function modifierComposante(array $composante);
}