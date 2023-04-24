<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Composante;

/**
 * @name ComposanteRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des composantes.
 *
 * @author Hugo Siliveri
 *
 */
class ComposanteRepository extends AbstractRepository
{

    /**
     * Retourne le nom de la table contenant les données de Composante.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Composante";
    }

    /**
     * Retourne la clé primaire de la table Composante.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idComposante";
    }

    /**
     * Retourne le nom de tous les attributs de la table Composante.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idComposante", "libComposante", "anneeDeTravail", "anneeDeValidation"];
    }

    /** Construit un objet Composante à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Composante
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Composante(
            $objetFormatTableau["idComposante"],
            $objetFormatTableau["libComposante"],
            $objetFormatTableau["anneeDeTravail"],
            $objetFormatTableau["anneeDeValidation"]
        );
    }
}