<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Intervenant;

/**
 * @name IntervenantRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des intervenants.
 *
 * @author Hugo Siliveri
 *
 */
class IntervenantRepository extends AbstractRepository
{

    /**
     * Retourne le nom de la table contenant les données d'Intervenant.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Intervenant";
    }

    /**
     * Retourne la clé primaire de la table Intervenant.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idIntervenant";
    }

    /**
     * Retourne le nom de tous les attributs de la table Intervenant.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idIntervenant", "nom", "prenom", "idStatut", "idDroit", "emailInstitutionnel", "emailUsage", "idIntervenantReferentiel", "deleted"];
    }

    /** Construit un objet Intervenant à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Intervenant
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Intervenant(
            $objetFormatTableau["idIntervenant"],
            $objetFormatTableau["nom"],
            $objetFormatTableau["prenom"],
            $objetFormatTableau["idStatut"],
            $objetFormatTableau["idDroit"],
            $objetFormatTableau["emailInstitutionnel"],
            $objetFormatTableau["emailUsage"],
            $objetFormatTableau["idIntervenantReferentiel"],
            $objetFormatTableau["deleted"]
        );
    }
}