<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\UniteService;
use PDO;
use PDOException;

/**
 * @name UniteServiceRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des unités services.
 *
 * @author Hugo Siliveri
 *
 */
class UniteServiceRepository extends AbstractRepository
{

    public function recupererPourAutoCompletion(array $uniteServiceArray, $limit = 10): array
    {
        try {
            $sql = "SELECT * from UniteService 
             WHERE idUSReferentiel LIKE :idUSReferentielTag OR libUS LIKE :libUSTag 
             ORDER BY idUSReferentiel, libUS
             LIMIT $limit";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idUSReferentielTag" => "%" . $uniteServiceArray["uniteService"] . "%",
                "libUSTag" => "%" . $uniteServiceArray["uniteService"] . "%",
            );
            $pdoStatement->execute($values);
            $pdoStatement->setFetchMode(PDO::FETCH_OBJ);
            return $pdoStatement->fetchAll();

        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors de la recherche dans la base de données.");
        }
    }

    public function ajouterSansIdUniteService(array $uniteService){
        try {
            $sql = "INSERT INTO UniteService
            (idUSReferentiel, libUS, nature, ancetre, anneeOuverture, anneeCloture, ECTS,
            heuresCM, heuresTD, heuresTP, heuresStage, heuresTerrain, semestre, saison, payeur, validite, deleted)
            VALUES 
            (:idUSReferentielTag, :libUSTag, :natureTag, :ancetreTag, :anneeOuvertureTag, :anneeClotureTag, :ECTSTag,
            :heuresCMTag, :heuresTDTag, :heuresTPTag, :heuresStageTag, :heuresTerrainTag, :semestreTag, :saisonTag, :payeurTag, 
             :validiteTag, :deletedTag)";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idUSReferentielTag" => $uniteService["idUSReferentiel"],
                "libUSTag" => $uniteService["libUS"],
                "natureTag" => $uniteService["nature"],
                "ancetreTag" => $uniteService["ancetre"],
                "anneeOuvertureTag" => $uniteService["anneeOuverture"],
                "anneeClotureTag" => $uniteService["anneeCloture"],
                "ECTSTag" => $uniteService["ECTS"],
                "heuresCMTag" => $uniteService["heuresCM"],
                "heuresTDTag" => $uniteService["heuresTD"],
                "heuresTPTag" => $uniteService["heuresTP"],
                "heuresStageTag" => $uniteService["heuresStage"],
                "heuresTerrainTag" => $uniteService["heuresTerrain"],
                "semestreTag" => $uniteService["semestre"],
                "saisonTag" => $uniteService["saison"],
                "payeurTag" => $uniteService["payeur"],
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

    /**
     * Retourne le nom de la table contenant les données de UniteService.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "UniteService";
    }

    /**
     * Retourne la clé primaire de la table UniteService.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idUniteService";
    }

    /**
     * Retourne le nom de tous les attributs de la table UniteService.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idUniteService", "idUSReferentiel", "libUS", "nature", "ancetre", "anneeOuverture", "anneeCloture", "ECTS",
            "heuresCM", "heuresTD", "heuresTP", "heuresStage", "heuresTerrain", "semestre", "saison", "payeur", "validite", "deleted"];
    }

    /** Construit un objet UniteService à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return UniteService
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new UniteService(
            $objetFormatTableau["idUniteService"],
            $objetFormatTableau["idUSReferentiel"],
            $objetFormatTableau["libUS"],
            $objetFormatTableau["nature"],
            $objetFormatTableau["ancetre"],
            $objetFormatTableau["anneeOuverture"],
            $objetFormatTableau["anneeCloture"],
            $objetFormatTableau["ECTS"],
            $objetFormatTableau["heuresCM"],
            $objetFormatTableau["heuresTD"],
            $objetFormatTableau["heuresTP"],
            $objetFormatTableau["heuresStage"],
            $objetFormatTableau["heuresTerrain"],
            $objetFormatTableau["semestre"],
            $objetFormatTableau["saison"],
            $objetFormatTableau["payeur"],
            $objetFormatTableau["validite"],
            $objetFormatTableau["deleted"]
        );
    }
}