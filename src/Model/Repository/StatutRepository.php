<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Statut;

/**
 * @name StatutRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des statuts.
 *
 * @author Hugo Siliveri
 *
 */
class StatutRepository extends AbstractRepository
{

    /**
     * Retourne le nom de la table contenant les données de Statut.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Statut";
    }

    /**
     * Retourne la clé primaire de la table Statut.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idStatut";
    }

    /**
     * Retourne le nom de tous les attributs de la table Statut.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idStatut", "libStatut", "nbHeures"];
    }

    /** Construit un objet Statut à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Statut
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Statut(
            $objetFormatTableau["idStatut"],
            $objetFormatTableau["libStatut"],
            $objetFormatTableau["nbHeures"],
        );
    }
}