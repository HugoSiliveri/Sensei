<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Emploi;
use PDOException;

/**
 * @name EmploiRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des emplois.
 *
 * @author Hugo Siliveri
 *
 */
class EmploiRepository extends AbstractRepository
{
    
    public function ajouterSansIdEmploi(array $emploi){
        try {
            $sql = "INSERT INTO Emploi(libEmploi) VALUES (:libEmploiTag)";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "libEmploiTag" => $emploi["libEmploi"],
            );
            $pdoStatement->execute($values);

            return null;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors d'insertion dans la base de données.");
        }
    }

    /**
     * Retourne le nom de la table contenant les données d'Emploi.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Emploi";
    }

    /**
     * Retourne la clé primaire de la table Emploi.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idEmploi";
    }

    /**
     * Retourne le nom de tous les attributs de la table Emploi.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idEmploi", "libEmploi"];
    }

    /** Construit un objet Emploi à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Emploi
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Emploi(
            $objetFormatTableau["idEmploi"],
            $objetFormatTableau["libEmploi"],
        );
    }
}