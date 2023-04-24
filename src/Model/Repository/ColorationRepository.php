<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Coloration;

/**
 * @name ColorationRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des colorations.
 *
 * @author Hugo Siliveri
 *
 */
class ColorationRepository extends AbstractRepository
{

    /**
     * Retourne le nom de la table contenant les données de Coloration.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Coloration";
    }

    /**
     * Retourne la clé primaire de la table Coloration.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idDepartement, idUniteServiceAnnee";
    }

    /**
     * Retourne le nom de tous les attributs de la table Coloration.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idDepartement", "idUniteServiceAnnee"];
    }

    /** Construit un objet Coloration à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Coloration
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Coloration(
            $objetFormatTableau["idDepartement"],
            $objetFormatTableau["idUniteServiceAnnee"]
        );
    }
}