<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Intervention;

/**
 * @name InterventionRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des interventions.
 *
 * @author Hugo Siliveri
 *
 */
class InterventionRepository extends AbstractRepository
{

    /**
     * Retourne le nom de la table contenant les données d'Intervention.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Intervention";
    }

    /**
     * Retourne la clé primaire de la table Intervention.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idIntervention";
    }

    /**
     * Retourne le nom de tous les attributs de la table Intervention.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idIntervention", "typeIntervention", "numeroGroupeIntervention", "volumeHoraire"];
    }

    /** Construit un objet Intervention à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Intervention
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Intervention(
            $objetFormatTableau["idIntervention"],
            $objetFormatTableau["typeIntervention"],
            $objetFormatTableau["numeroGroupeIntervention"],
            $objetFormatTableau["volumeHoraire"]
        );
    }
}