<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface DroitServiceInterface
{
    /**
     * @param int $idDroit
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idDroit): AbstractDataObject;

    /**
     * @throws ServiceException
     */
    public function verifierDroits();

    /**
     * @throws ServiceException
     */
    public function creerDroit(array $droit);

    /**
     * @throws ServiceException
     */
    public function modifierDroit(array $droit);

    public function supprimerDroit(int $idDroit);

    public function recupererDroits(): array;
}