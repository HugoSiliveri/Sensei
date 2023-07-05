<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface DeclarationServiceServiceInterface
{
    public function recupererParIdIntervenant(int $idIntervenant): array;

    /**
     * @param int $idUniteServiceAnnee
     * @return array
     */
    public function recupererParIdUSA(int $idUniteServiceAnnee): array;

    /**
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idDeclarationService): AbstractDataObject;

    /**
     * @param int $idIntervenant
     * @param int $annee
     * @return array
     */
    public function recupererVueParIdIntervenantAnnuel(int $idIntervenant, int $annee): array;

    /**
     * @param int $idIntervenant
     * @return array
     */
    public function recupererVueParIdIntervenant(int $idIntervenant): array;

    public function verifierPhaseDeVoeu(int $annee, int $idDepartement): bool;

    public function supprimerDeclarationService(int $idDeclarationService);
}