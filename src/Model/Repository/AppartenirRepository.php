<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Appartenir;
use PDOException;

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
    public function recupererParIdUniteService(int $idUniteService)
    {
        try {
            $sql = "SELECT * FROM Appartenir WHERE idUniteService =:idUniteServiceTag";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idUniteServiceTag" => $idUniteService,
            );
            $pdoStatement->execute($values);

            $objetsFormatTableau = $pdoStatement->fetchALl();

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

    public function verifierAppartenance(int $idUniteService, int $idDepartement): ?array{
        try {
            $sql = "SELECT * from UniteService WHERE idUniteService = (
            SELECT MAX(idUniteService)
            FROM UniteService)";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);
            $pdoStatement->execute();

            $objet = $pdoStatement->fetch();

            if (!$objet){
                return null;
            }
            return $objet;

        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors de la recherche dans la base de données.");
        }
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

    public function ajouterSansIdAppartenir(array $uniteService)
    {
        try {
            $sql = "INSERT INTO Appartenir(idDepartement, idUniteService) VALUES (:idDepartementTag, :idUniteServiceTag)";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idDepartementTag" => $uniteService["idDepartement"],
                "idUniteServiceTag" => $uniteService["idUniteService"],
            );
            $pdoStatement->execute($values);

            return null;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors d'insertion dans la base de données.");
        }
    }

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
}