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
    public function recupererReferentiels(int $annee): array
    {
        try {
            $sql = "SELECT *
            FROM UniteServiceAnnee usa
            JOIN UniteService us ON usa.idUniteService = us.idUniteService
            JOIN Nature na ON na.idNature = us.nature
            WHERE millesime =:millesimeTag AND libNature = 'UR'
            ORDER BY millesime DESC, libUSA";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "millesimeTag" => $annee,
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
            $objetFormatTableau["heuresInnovationPedagogique"],
            $objetFormatTableau["nbGroupesInnovationPedagogique"],
            $objetFormatTableau["validite"],
            $objetFormatTableau["deleted"],
        );
    }

    public function recupererDecharges(int $annee): array
    {
        try {
            $sql = "SELECT *
            FROM UniteServiceAnnee usa
            JOIN UniteService us ON usa.idUniteService = us.idUniteService
            JOIN Nature na ON na.idNature = us.nature
            WHERE millesime =:millesimeTag AND libNature = 'DE'
            ORDER BY millesime DESC, libUSA";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "millesimeTag" => $annee,
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

    public function recupererParUniteService(int $idUniteService): array
    {
        try {
            $sql = "SELECT DISTINCT *
            FROM UniteServiceAnnee WHERE idUniteService =:idUniteServiceTag
            ORDER BY millesime DESC, libUSA";

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

    public function recupererParUniteServiceAvecAnnee(int $idUniteService, int $millesime): ?AbstractDataObject
    {
        try {
            $sql = "SELECT DISTINCT *
            FROM UniteServiceAnnee WHERE idUniteService =:idUniteServiceTag AND millesime =:millesimeTag
            ORDER BY millesime DESC, libUSA";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idUniteServiceTag" => $idUniteService,
                "millesimeTag" => $millesime
            );
            $pdoStatement->execute($values);

            $objetFormatTableau = $pdoStatement->fetch();

            if (!$objetFormatTableau) {
                return null;
            }
            return $this->construireDepuisTableau($objetFormatTableau);
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors de la recherche dans la base de données.");
        }
    }

    public function recupererUnitesServicesPourUneAnneePourUnDepartement($annee, $idDepartement): array
    {
        try {
            $sql = "SELECT usa.idUniteServiceAnnee, usa.idDepartement, usa.idUniteService, libUSA, millesime, usa.heuresCM, nbGroupesCM,
            usa.heuresTD, nbGroupesTD, usa.heuresTP, nbGroupesTP, usa.heuresStage, nbGroupesStage, usa.heuresTerrain,
            nbGroupesTerrain, usa.heuresInnovationPedagogique, nbGroupesInnovationPedagogique, usa.validite, usa.deleted
            FROM UniteServiceAnnee usa
            LEFT JOIN Coloration c ON c.idUniteServiceAnnee = usa.idUniteServiceAnnee
            JOIN UniteService us ON us.idUniteService = usa.idUniteService
            WHERE c.idUniteServiceAnnee IS NULL AND millesime =:anneeTag AND usa.idDepartement=:idDepartementTag
            ORDER BY idUSReferentiel, libUSA";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "anneeTag" => $annee,
                "idDepartementTag" => $idDepartement
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

    public function recupererUniteServiceAnneeUniquementColoration($annee, $idDepartement): array
    {
        try {
            $sql = "SELECT * FROM UniteServiceAnnee usa
            LEFT JOIN Coloration c ON c.idUniteServiceAnnee = usa.idUniteServiceAnnee
            JOIN UniteService us ON us.idUniteService = usa.idUniteService
            WHERE c.idDepartement =:idDepartementTag AND millesime =:anneeTag AND usa.idDepartement!=:idDepartementTag2
            ORDER BY idUSReferentiel, libUSA";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "anneeTag" => $annee,
                "idDepartementTag" => $idDepartement,
                "idDepartementTag2" => $idDepartement
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

    public function ajouterSansIdUniteServiceAnnee(array $uniteService)
    {
        try {
            $sql = "INSERT INTO UniteServiceAnnee
            (idDepartement, idUniteService, libUSA, millesime, heuresCM, nbGroupesCM, heuresTD, nbGroupesTD,
            heuresTP, nbGroupesTP, heuresStage, nbGroupesStage, heuresTerrain, nbGroupesTerrain, heuresInnovationPedagogique, 
             nbGroupesInnovationPedagogique, validite, deleted)
            VALUES 
            (:idDepartementTag, :idUniteServiceTag, :libUSATag, :millesimeTag, :heuresCMTag, :nbGroupesCMTag, :heuresTDTag, 
             :nbGroupesTDTag, :heuresTPTag, :nbGroupesTPTag, :heuresStageTag, :nbGroupesStageTag, :heuresTerrainTag, :nbGroupesTerrainTag,
             :heuresInnovationPedagogiqueTag, :nbGroupesInnovationPedagogiqueTag, :validiteTag, :deletedTag)";

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
                "heuresInnovationPedagogiqueTag" => $uniteService["heuresInnovationPedagogique"],
                "nbGroupesInnovationPedagogiqueTag" => $uniteService["nbGroupesInnovationPedagogique"]
            );
            $pdoStatement->execute($values);

            return null;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors d'insertion dans la base de données.");
        }
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
            "heuresInnovationPedagogique", "nbGroupesInnovationPedagogique", "nbGroupesTerrain", "validite", "deleted"];
    }
}