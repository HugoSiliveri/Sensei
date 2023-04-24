<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Appartenir;

/**
 * @name AppartenirRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des appartenances.
 *
 * @author Hugo Siliveri
 *
 */
class AppartenirRepository extends AbstractRepository
{

    /**
     * Retourne le nom de la table contenant les données de Appartenir.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Appartenir";
    }

    /**
     * Retourne la clé primaire de la table Appartenir.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idAppartenir";
    }

    /**
     * Retourne le nom de tous les attributs de la table Appartenir.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idAppartenir", "idDepartement, idUniteService"];
    }

    /** Construit un objet Appartenir à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Appartenir
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Appartenir(
            $objetFormatTableau["idAppartenir"],
            $objetFormatTableau["idDepartement"],
            $objetFormatTableau["idUniteService"]
        );
    }
}