<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\UniteServiceAnnee;
use App\Sensei\Service\Exception\ServiceException;

interface UniteServiceAnneeServiceInterface
{
    /**
     * @param int $idUniteServiceAnnee
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idUniteServiceAnnee): AbstractDataObject;

    public function renouvelerUniteServiceAnnee(UniteServiceAnnee $usa, int $annee);

    public function recupererUnitesServicesPourUneAnneePourUnDepartement(int $anneeActuelle, int $idDepartement): array;

    public function recupererDecharges(int $annee): array;

    public function recupererReferentiels(int $annee): array;

    /**
     * @param int $idUniteService
     * @return array
     */
    public function recupererParUniteService(int $idUniteService): array;

    public function recupererUnitesServicesAnneeUniquementColoration(int $anneeActuelle, int $idDepartement): array;

    /**
     * @throws ServiceException
     */
    public function modifierUniteServiceAnnee(array $uniteServiceAnnee);

    /**
     * @throws ServiceException
     */
    public function creerUniteServiceAnnee(array $uniteServiceAnnee);

    public function recupererParUniteServiceAvecAnnee(int $idUniteService, int $millesime): ?AbstractDataObject;
}