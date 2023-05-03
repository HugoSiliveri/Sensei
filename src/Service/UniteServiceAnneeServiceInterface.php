<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface UniteServiceAnneeServiceInterface
{
    /**
     * @param int $idUniteServiceAnnee
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idUniteServiceAnnee): AbstractDataObject;
}