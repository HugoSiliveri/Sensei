<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface EmploiServiceInterface
{
    /**
     * @param int $idEmploi
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idEmploi): AbstractDataObject;

    public function supprimerEmploi(int $idEmploi);

    public function recupererEmplois(): array;

    /**
     * @throws ServiceException
     */
    public function modifierEmploi(array $emploi);

    /**
     * @throws ServiceException
     */
    public function creerEmploi(array $emploi);
}