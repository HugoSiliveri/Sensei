<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\ServiceAnnuel;
use PDOException;

/**
 * @name ServiceAnnuelRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des services annuels.
 *
 * @author Hugo Siliveri
 *
 */
class ServiceAnnuelRepository extends AbstractRepository
{

    /**
     * Retourne la liste des services annuels de l'intervenant dont l'identifiant est passé en paramètre
     *
     * @param $idIntervenant
     * @return array
     */
    public function recupererParIntervenant($idIntervenant): array
    {
        try {
            $sql = "SELECT * from ServiceAnnuel WHERE idIntervenant=:idIntervenantTag ORDER BY millesime DESC";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idIntervenantTag" => $idIntervenant,
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

    /** Construit un objet ServiceAnnuel à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return ServiceAnnuel
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new ServiceAnnuel(
            $objetFormatTableau["idServiceAnnuel"],
            $objetFormatTableau["idDepartement"],
            $objetFormatTableau["idIntervenant"],
            $objetFormatTableau["millesime"],
            $objetFormatTableau["idEmploi"],
            $objetFormatTableau["serviceStatuaire"],
            $objetFormatTableau["serviceFait"],
            $objetFormatTableau["delta"],
            $objetFormatTableau["deleted"]
        );
    }

    /**
     * Retourne le nom de la table contenant les données de ServiceAnnuel.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "ServiceAnnuel";
    }

    /**
     * Retourne la clé primaire de la table ServiceAnnuel.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idServiceAnnuel";
    }

    /**
     * Retourne le nom de tous les attributs de la table ServiceAnnuel.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idServiceAnnuel", "idDepartement", "idIntervenant", "millesime", "idEmploi", "serviceStatuaire", "serviceFait", "delta", "deleted"];
    }
}