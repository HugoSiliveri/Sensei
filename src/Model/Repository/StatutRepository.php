<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Statut;
use PDOException;

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

    public function ajouterSansIdStatut(array $statut)
    {
        try {
            $sql = "INSERT INTO Statut(libStatut, nbHeures) VALUES (:libStatutTag, :nbHeuresTag)";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "libStatutTag" => $statut["libStatut"],
                "nbHeuresTag" => $statut["nbHeures"]
            );
            $pdoStatement->execute($values);

            return null;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors d'insertion dans la base de données.");
        }
    }

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