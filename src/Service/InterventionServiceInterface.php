<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface InterventionServiceInterface
{
    /**
     * @param int $idIntervention
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idIntervention): AbstractDataObject;
}