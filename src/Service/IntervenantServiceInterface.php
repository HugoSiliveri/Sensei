<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface IntervenantServiceInterface
{
    public function recupererIntervenants(): array;

    public function recupererIntervenantsAvecAnneeEtDepartementVacataire(int $annee, int $idDepartement): array;

    /**
     * @param int $idIntervenant
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idIntervenant): AbstractDataObject;

    public function recupererIntervenantsAvecAnneeEtDepartementGestionnaire(int $annee, int $idDepartement): array;

    /**
     * @throws ServiceException
     */
    public function rechercherIntervenant(string $recherche): AbstractDataObject;

    public function recupererIntervenantsAvecAnneeEtDepartementHorsUM(int $annee, int $idDepartement);

    public function recupererParEmailInstitutionnel(string $email): ?AbstractDataObject;

    /**
     * @throws ServiceException
     */
    public function creerIntervenant(array $intervenant);

    public function recupererIntervenantsAvecAnneeEtDepartementUMHorsFds(int $annee, int $idDepartement): array;

    public function recupererIntervenantsAvecAnneeEtDepartementPermanent(int $annee, int $idDepartement): array;

    public function recupererRequeteIntervenant(): array;

    /**
     * @throws ServiceException
     */
    public function modifierIntervenant(array $intervenant);

    public function recupererParUID(string $uid): ?AbstractDataObject;
}