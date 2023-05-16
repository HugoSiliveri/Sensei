<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface EtatServiceInterface
{
    /**
     * @param int $idEtat
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idEtat): AbstractDataObject;

    public function creerEtat(array $etat);

    public function recupererEtats();

    public function supprimerEtat(int $idEtat);

    /**
     * @throws ServiceException
     */
    public function modifierEtat(array $etat);
}