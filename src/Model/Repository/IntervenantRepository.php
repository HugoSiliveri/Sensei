<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Intervenant;
use PDO;
use PDOException;

/**
 * @name IntervenantRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des intervenants.
 *
 * @author Hugo Siliveri
 *
 */
class IntervenantRepository extends AbstractRepository
{

    /**
     * Retourne l'intervenant qui possède le même idIntervenantReferentiel que celui en paramètre.
     * Retourne null s'il ne trouve pas
     *
     * @param $idIntervenantReferentiel
     * @return AbstractDataObject|null
     */
    public function recupererParUID($idIntervenantReferentiel): ?AbstractDataObject
    {
        try {
            $sql = "SELECT * from Intervenant WHERE idIntervenantReferentiel=:uidTag";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "uidTag" => $idIntervenantReferentiel,
            );
            $pdoStatement->execute($values);

            $objetFormatTableau = $pdoStatement->fetch();

            if ($objetFormatTableau !== false) {
                return $this->construireDepuisTableau($objetFormatTableau);
            }
            return null;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors de la recherche dans la base de données.");
        }

    }

    /**
     * @param $annee
     * @param $idDepartement
     * @return array
     */
    public function recupererIntervenantsAvecAnneeEtDepartementNonVacataire($annee, $idDepartement): array
    {
        try {
            $sql = "SELECT * 
            from Intervenant i
            JOIN ServiceAnnuel s On s.idIntervenant = i.idIntervenant
            WHERE millesime=:millesimeTag AND idDepartement=:idDepartementTag AND idEmploi != 7";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "millesimeTag" => $annee,
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

    /**
     * @param $annee
     * @param $idDepartement
     * @return array
     */
    public function recupererIntervenantsAvecAnneeEtDepartementVacataire($annee, $idDepartement): array
    {
        try {
            $sql = "SELECT * 
            from Intervenant i
            JOIN ServiceAnnuel s On s.idIntervenant = i.idIntervenant
            WHERE millesime=:millesimeTag AND idDepartement=:idDepartementTag AND idEmploi = 7";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "millesimeTag" => $annee,
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

    /**
     * Construit un objet Intervenant à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Intervenant
     */
    public function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Intervenant(
            $objetFormatTableau["idIntervenant"],
            $objetFormatTableau["nom"],
            $objetFormatTableau["prenom"],
            $objetFormatTableau["idStatut"],
            $objetFormatTableau["idDroit"],
            $objetFormatTableau["emailInstitutionnel"],
            $objetFormatTableau["emailUsage"],
            $objetFormatTableau["idIntervenantReferentiel"],
            $objetFormatTableau["deleted"]
        );
    }

    public function ajouterSansIdIntervenant(array $intervenant)
    {
        try {
            $sql = "INSERT INTO Intervenant(nom, prenom, idStatut, idDroit, emailInstitutionnel, emailUsage, idIntervenantReferentiel, deleted)
            VALUES (:nomTag, :prenomTag, :idStatutTag, :idDroitTag, :emailInstitutionnelTag, :emailUsageTag, :idIntervenantReferentielTag,
                    :deletedTag)";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "nomTag" => $intervenant["nom"],
                "prenomTag" => $intervenant["prenom"],
                "idStatutTag" => $intervenant["idStatut"],
                "idDroitTag" => $intervenant["idDroit"],
                "emailInstitutionnelTag" => $intervenant["emailInstitutionnel"],
                "emailUsageTag" => $intervenant["emailUsage"],
                "idIntervenantReferentielTag" => $intervenant["idIntervenantReferentiel"],
                "deletedTag" => $intervenant["deleted"]
            );
            $pdoStatement->execute($values);

            return null;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors d'insertion dans la base de données.");
        }
    }

    /**
     * Retourne l'intervenant qui possède le même emailInstitutionnel que celui en paramètre.
     * Retourne null s'il ne trouve pas
     *
     * @param $emailInstitutionnel
     * @return AbstractDataObject|null
     */
    public function recupererParEmailInstitutionnel($emailInstitutionnel): ?AbstractDataObject
    {
        try {
            $sql = "SELECT * from Intervenant WHERE emailInstitutionnel=:uidTag";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "clePrimaireTag" => $emailInstitutionnel,
            );
            $pdoStatement->execute($values);

            $objetFormatTableau = $pdoStatement->fetch();

            if ($objetFormatTableau !== false) {
                return $this->construireDepuisTableau($objetFormatTableau);
            }
            return null;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors de la recherche dans la base de données.");
        }
    }

    public function recupererPourAutoCompletion(array $intervenantArray, $limit = 10): array
    {
        try {
            $sql = "SELECT * from Intervenant 
             WHERE nom LIKE :nomTag OR prenom LIKE :prenomTag OR idIntervenantReferentiel LIKE :idIntervenantReferentielTag
             ORDER BY nom, prenom, idIntervenantReferentiel
             LIMIT $limit";

            $values = array(
                "nomTag" => "%" . $intervenantArray["intervenant"] . "%",
                "prenomTag" => "%" . $intervenantArray["intervenant"] . "%",
                "idIntervenantReferentielTag" => "%" . $intervenantArray["intervenant"] . "%",
            );

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);
            $pdoStatement->execute($values);

            $pdoStatement->setFetchMode(PDO::FETCH_OBJ);
            return $pdoStatement->fetchAll();

        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors de la recherche dans la base de données.");
        }

    }

    /**
     * Retourne le nom de la table contenant les données d'Intervenant.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Intervenant";
    }

    /**
     * Retourne la clé primaire de la table Intervenant.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idIntervenant";
    }

    /**
     * Retourne le nom de tous les attributs de la table Intervenant.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idIntervenant", "nom", "prenom", "idStatut", "idDroit", "emailInstitutionnel", "emailUsage", "idIntervenantReferentiel", "deleted"];
    }
}