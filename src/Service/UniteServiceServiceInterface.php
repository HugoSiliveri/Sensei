<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface UniteServiceServiceInterface
{
    public function recupererUnitesServices(): array;

    /**
     * @throws ServiceException
     */
    public function rechercherUniteService(string $recherche): AbstractDataObject;

    /**
     * @throws ServiceException
     */
    public function recupererParIdUSReferentiel(string $idUSReferentiel): AbstractDataObject;

    public function recupererRequeteUniteService(): array;

    /**
     * @param int $idUniteService
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idUniteService): AbstractDataObject;

    /**
     * @throws ServiceException
     */
    public function recupererDernierUniteService(): AbstractDataObject;

    /**
     * @throws ServiceException
     */
    public function modifierUniteService(array $uniteService);

    public function recupererParAnneeOuverture(int $annee): array;

    /**
     * @throws ServiceException
     */
    public function creerUniteService(array $uniteService);
}