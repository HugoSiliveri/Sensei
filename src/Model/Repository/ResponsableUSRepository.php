<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\ResponsableUS;
use PDOException;

/**
 * @name ResponsableUSRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des responsables d'unité service.
 *
 * @author Hugo Siliveri
 *
 */
class ResponsableUSRepository extends AbstractRepository
{

    public function recupererParIdIntervenantAnnuel(int $idIntervenant, int $millesime)
    {
        try {
            $sql = "SELECT DISTINCT * FROM ResponsableUS r
                  JOIN UniteServiceAnnee usa ON r.idUniteServiceAnnee = usa.idUniteServiceAnnee
                  WHERE idIntervenant =:idIntervenantTag AND millesime =:millesimeTag";


            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idIntervenantTag" => $idIntervenant,
                "millesimeTag" => $millesime
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
     * Retourne le nom de la table contenant les données de ResponsableUS.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "ResponsableUS";
    }

    /**
     * Retourne la clé primaire de la table ResponsableUS.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idIntervenant, idUniteServiceAnnee";
    }

    /**
     * Retourne le nom de tous les attributs de la table ResponsableUS.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idIntervenant", "idUniteServiceAnnee"];
    }

    /** Construit un objet ResponsableUS à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return ResponsableUS
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new ResponsableUS(
            $objetFormatTableau["idIntervenant"],
            $objetFormatTableau["idUniteServiceAnnee"],
        );
    }
}
