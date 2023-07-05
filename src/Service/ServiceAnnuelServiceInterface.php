<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\ServiceAnnuel;
use App\Sensei\Service\Exception\ServiceException;

interface ServiceAnnuelServiceInterface
{
    /**
     * @param int $idIntervenant
     * @return array
     * @throws ServiceException
     */
    public function recupererParIntervenant(int $idIntervenant): array;

    /**
     * @throws ServiceException
     */
    public function creerServiceAnnuel(array $serviceAnnuel);

    public function renouvelerServiceAnnuel(ServiceAnnuel $serviceAnnuel, int $annee);

    public function recupererParDepartementAnnuel(int $idDepartement, int $annee): ?array;

    public function recupererParIdentifiant(int $idServiceAnnuel): ?AbstractDataObject;

    /**
     * @param int $idIntervenant
     * @param int $millesime
     * @return AbstractDataObject|null
     */
    public function recupererParIntervenantAnnuel(int $idIntervenant, int $millesime): ?AbstractDataObject;

    /**
     * @throws ServiceException
     */
    public function recupererPlusRecentDuDepartement(int $idDepartement);

    /**
     * @throws ServiceException
     */
    public function modifierServiceAnnuel(array $serviceAnnuel);

    public function recupererParIntervenantAnnuelPlusRecent(int $idIntervenant): ?AbstractDataObject;
}