<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Intervention;
use PDOException;

/**
 * @name InterventionRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des interventions.
 *
 * @author Hugo Siliveri
 *
 */
class InterventionRepository extends AbstractRepository
{

    public function ajouterSansIdIntervention(array $intervention)
    {
        try {
            $sql = "INSERT INTO Intervention(typeIntervention, numeroGroupeIntervention, volumeHoraire) 
            VALUES (:typeInterventionTag, :numeroGroupeInterventionTag, :volumeHoraireTag)";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "typeInterventionTag" => $intervention["typeIntervention"],
                "numeroGroupeInterventionTag" => $intervention["numeroGroupeIntervention"],
                "volumeHoraireTag" => $intervention["volumeHoraire"]
            );
            $pdoStatement->execute($values);

            return null;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors d'insertion dans la base de données.");
        }
    }

    public function recupererParInfos(array $intervention)
    {
        try {
            $sql = "SELECT * FROM Intervention WHERE typeIntervention = :typeInterventionTag AND 
            numeroGroupeIntervention = :numeroGroupeInterventionTag AND volumeHoraire = :volumeHoraireTag";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "typeInterventionTag" => $intervention["typeIntervention"],
                "numeroGroupeInterventionTag" => $intervention["numeroGroupeIntervention"],
                "volumeHoraireTag" => $intervention["volumeHoraire"]
            );
            $pdoStatement->execute($values);

            $tab = $pdoStatement->fetch();
            if (!$tab) {
                return [];
            }
            return $tab;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors d'insertion dans la base de données.");
        }
    }

    public function recupererDernierIntervention()
    {
        try {
            $sql = "SELECT *
                    FROM Intervention
                    WHERE idIntervention = (SELECT MAX(idIntervention)
						                    FROM Intervention)";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $pdoStatement->execute();

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

    /** Construit un objet Intervention à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Intervention
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Intervention(
            $objetFormatTableau["idIntervention"],
            $objetFormatTableau["typeIntervention"],
            $objetFormatTableau["numeroGroupeIntervention"],
            $objetFormatTableau["volumeHoraire"]
        );
    }

    /**
     * Retourne le nom de la table contenant les données d'Intervention.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Intervention";
    }

    /**
     * Retourne la clé primaire de la table Intervention.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idIntervention";
    }

    /**
     * Retourne le nom de tous les attributs de la table Intervention.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idIntervention", "typeIntervention", "numeroGroupeIntervention", "volumeHoraire"];
    }
}