<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface PayeurServiceInterface
{
    /**
     * @param int $idPayeur
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idPayeur): AbstractDataObject;

    /**
     * @throws ServiceException
     */
    public function creerPayeur(array $payeur);

    /**
     * @throws ServiceException
     */
    public function modifierPayeur(array $payeur);

    public function supprimerPayeur(int $idPayeur);

    public function recupererPayeurs(): array;
}