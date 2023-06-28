<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface SaisonServiceInterface
{
    /**
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idSaison): AbstractDataObject;
}