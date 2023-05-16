<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Composante;
use PDOException;

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

    public function ajouterSansIdComposante(array $composante) {
        try {
            $sql = "INSERT INTO Composante(libComposante, anneeDeTravail, anneeDeValidation) 
            VALUES (:libComposanteTag, :anneeDeTravailTag, :anneeDeValidationTag)";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "libComposanteTag" => $composante["libComposante"],
                "anneeDeTravailTag" => $composante["anneeDeTravail"],
                "anneeDeValidationTag" => $composante["anneeDeValidation"]
            );
            $pdoStatement->execute($values);

            return null;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors d'insertion dans la base de données.");
        }
    }

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