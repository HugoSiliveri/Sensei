<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Droit;

/**
 * @name DroitRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des droits.
 *
 * @author Hugo Siliveri
 *
 */
class DroitRepository extends AbstractRepository
{

    /**
     * Retourne le nom de la table contenant les données de Droit.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Droit";
    }

    /**
     * Retourne la clé primaire de la table Droit.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idDroit";
    }

    /**
     * Retourne le nom de tous les attributs de la table Droit.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idDroit", "typeDroit"];
    }

    /** Construit un objet Droit à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Droit
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Droit(
            $objetFormatTableau["idDroit"],
            $objetFormatTableau["typeDroit"],
        );
    }
}