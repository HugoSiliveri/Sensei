<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Departement;

/**
 * @name DepartementRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des départements.
 *
 * @author Hugo Siliveri
 *
 */
class DepartementRepository extends AbstractRepository
{

    /**
     * Retourne le nom de la table contenant les données de Departement.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Departement";
    }

    /**
     * Retourne la clé primaire de la table Departement.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idDepartement";
    }

    /**
     * Retourne le nom de tous les attributs de la table Departement.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idDepartement", "libDepartement", "codeLettre", "reportMax", "idComposante", "idEtat"];
    }

    /** Construit un objet Departement à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Departement
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Departement(
            $objetFormatTableau["idDepartement"],
            $objetFormatTableau["libDepartement"],
            $objetFormatTableau["codeLettre"],
            $objetFormatTableau["reportMax"],
            $objetFormatTableau["idComposante"],
            $objetFormatTableau["idEtat"]
        );
    }
}