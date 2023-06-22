<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Voeu;
use PDOException;

/**
 * @name VoeuRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des vœux.
 *
 * @author Hugo Siliveri
 *
 */
class VoeuRepository extends AbstractRepository
{

    /**
     * Retourne la liste des services annuels de l'intervenant dont l'identifiant est passé en paramètre
     *
     * @param $idIntervenant
     * @return array
     */
    public function recupererVueParIntervenant($idIntervenant): array
    {
        try {
            $sql = "SELECT millesime, idUSReferentiel, libUSA, numGroupe, volumeHoraire, typeIntervention
            FROM vueVoeuPourServiceAnnuel WHERE idIntervenant=:idIntervenantTag
            ORDER BY millesime DESC";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idIntervenantTag" => $idIntervenant,
            );
            $pdoStatement->execute($values);

            return $pdoStatement->fetchAll();
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors de la recherche dans la base de données.");
        }
    }

    public function recupererVueParIntervenantAnnuel($idIntervenant, $annee): array
    {
        try {
            $sql = "SELECT *
            FROM vueVoeuPourServiceAnnuel WHERE idIntervenant=:idIntervenantTag AND millesime=:millesimeTag
            ORDER BY millesime DESC";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idIntervenantTag" => $idIntervenant,
                "millesimeTag" => $annee
            );
            $pdoStatement->execute($values);

            return $pdoStatement->fetchAll();
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors de la recherche dans la base de données.");
        }
    }

    public function recupererParIdUSA(int $idUniteServiceAnnee)
    {
        try {
            $sql = "SELECT DISTINCT * FROM Voeu WHERE idUniteServiceAnnee=:idUniteServiceAnneeTag";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idUniteServiceAnneeTag" => $idUniteServiceAnnee,
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

    /** Construit un objet Voeu à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Voeu
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Voeu(
            $objetFormatTableau["idVoeu"],
            $objetFormatTableau["idIntervenant"],
            $objetFormatTableau["idUniteServiceAnnee"],
            $objetFormatTableau["idIntervention"]
        );
    }

    /**
     * Retourne le nom de la table contenant les données de Voeu.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Voeu";
    }

    /**
     * Retourne la clé primaire de la table Voeu.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idVoeu";
    }

    /**
     * Retourne le nom de tous les attributs de la table Voeu.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idVoeu", "idIntervenant", "idUniteServiceAnnee", "idIntervention"];
    }

}