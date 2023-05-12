<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\UniteServiceAnnee;
use PDOException;

/**
 * @name UniteServiceAnneeRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des unités services annuelles.
 *
 * @author Hugo Siliveri
 *
 */
class UniteServiceAnneeRepository extends AbstractRepository
{
    public function recupererParUniteService(int $idUniteService)
    {
        try {
            $sql = "SELECT DISTINCT *
            FROM UniteServiceAnnee WHERE idUniteService =:idUniteServiceTag
            ORDER BY millesime DESC";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idUniteServiceTag" => $idUniteService,
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

    public function ajouterSansIdUniteServiceAnnee(array $uniteService){
        try {
            $sql = "INSERT INTO UniteServiceAnnee
            (idDepartement, idUniteService, libUSA, millesime, heuresCM, nbGroupesCM, heuresTD, nbGroupesTD,
            heuresTP, nbGroupesTP, heuresStage, nbGroupesStage, heuresTerrain, nbGroupesTerrain, validite, deleted)
            VALUES 
            (:idDepartementTag, :idUniteServiceTag, :libUSATag, :millesimeTag, :heuresCMTag, :nbGroupesCMTag, :heuresTDTag, 
             :nbGroupesTDTag, :heuresTPTag, :nbGroupesTPTag, :heuresStageTag, :nbGroupesStageTag, :heuresTerrainTag, :nbGroupesTerrainTag,
             :validiteTag, :deletedTag)";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idDepartementTag" => $uniteService["idDepartement"],
                "idUniteServiceTag" => $uniteService["idUniteService"],
                "libUSATag" => $uniteService["libUSA"],
                "millesimeTag" => $uniteService["millesime"],
                "heuresCMTag" => $uniteService["heuresCM"],
                "nbGroupesCMTag" => $uniteService["nbGroupesCM"],
                "heuresTDTag" => $uniteService["heuresTD"],
                "nbGroupesTDTag" => $uniteService["nbGroupesTD"],
                "heuresTPTag" => $uniteService["heuresTP"],
                "nbGroupesTPTag" => $uniteService["nbGroupesTP"],
                "heuresStageTag" => $uniteService["heuresStage"],
                "nbGroupesStageTag" => $uniteService["nbGroupesStage"],
                "heuresTerrainTag" => $uniteService["heuresTerrain"],
                "nbGroupesTerrainTag" => $uniteService["nbGroupesTerrain"],
                "validiteTag" => $uniteService["validite"],
                "deletedTag" => $uniteService["deleted"],
            );
            $pdoStatement->execute($values);

            return null;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors d'insertion dans la base de données.");
        }
    }

    /** Construit un objet UniteServiceAnnee à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return UniteServiceAnnee
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new UniteServiceAnnee(
            $objetFormatTableau["idUniteServiceAnnee"],
            $objetFormatTableau["idDepartement"],
            $objetFormatTableau["idUniteService"],
            $objetFormatTableau["libUSA"],
            $objetFormatTableau["millesime"],
            $objetFormatTableau["heuresCM"],
            $objetFormatTableau["nbGroupesCM"],
            $objetFormatTableau["heuresTD"],
            $objetFormatTableau["nbGroupesTD"],
            $objetFormatTableau["heuresTP"],
            $objetFormatTableau["nbGroupesTP"],
            $objetFormatTableau["heuresStage"],
            $objetFormatTableau["nbGroupesStage"],
            $objetFormatTableau["heuresTerrain"],
            $objetFormatTableau["nbGroupesTerrain"],
            $objetFormatTableau["validite"],
            $objetFormatTableau["deleted"],
        );
    }

    /**
     * Retourne le nom de la table contenant les données de UniteServiceAnnee.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "UniteServiceAnnee";
    }

    /**
     * Retourne la clé primaire de la table UniteServiceAnnee.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idUniteServiceAnnee";
    }

    /**
     * Retourne le nom de tous les attributs de la table UniteServiceAnnee.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idUniteServiceAnnee", "idDepartement", "idUniteService", "libUSA", "millesime", "heuresCM", "nbGroupesCM",
            "heuresTD", "nbGroupesTD", "heuresTP", "nbGroupesTP", "heuresStage", "nbGroupesStage", "heuresTerrain",
            "nbGroupesTerrain", "validite", "deleted"];
    }
}