<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface SemestreServiceInterface
{
    /**
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idSemestre): AbstractDataObject;
}