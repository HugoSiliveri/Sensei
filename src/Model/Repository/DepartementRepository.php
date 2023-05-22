<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Departement;
use PDOException;

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
    public function ajouterSansIdDepartement(array $departement)
    {
        try {
            $sql = "INSERT INTO Departement(libDepartement, codeLettre, reportMax, idComposante, idEtat) 
            VALUES (:libDepartementTag, :codeLettreTag, :reportMaxTag, :idComposanteTag, :idEtatTag)";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "libDepartementTag" => $departement["libDepartement"],
                "codeLettreTag" => $departement["codeLettre"],
                "reportMaxTag" => $departement["reportMax"],
                "idComposanteTag" => $departement["idComposante"],
                "idEtatTag" => $departement["idEtat"]
            );

            $pdoStatement->execute($values);
            return null;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors d'insertion dans la base de données.");
        }
    }

    public function recupererParLibelle(string $lib): array
    {
        try {
            $sql = "SELECT * from Departement WHERE libDepartement=:libDepartementTag";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "libDepartementTag" => $lib,
            );
            $pdoStatement->execute($values);

            $objetsFormatTableau = $pdoStatement->fetchAll();
            $objets = [];
            foreach ($objetsFormatTableau as $objetFormatTableau) {
                $objets[] = $this->construireDepuisTableau($objetFormatTableau);
            }
            return $objets;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors de la recherche dans la base de données.");
        }
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
}