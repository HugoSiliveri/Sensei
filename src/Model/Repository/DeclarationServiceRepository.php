<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\DeclarationService;
use PDOException;

class DeclarationServiceRepository extends AbstractRepository
{
    /**
     * Retourne la liste des services annuels de l'intervenant dont l'identifiant est passé en paramètre
     *
     * @param $idIntervenant
     * @return array
     */
    public function recupererVueParIdIntervenant($idIntervenant): array
    {
        try {
            $sql = "SELECT *
            FROM vueInfosService WHERE idIntervenant=:idIntervenantTag
            ORDER BY millesime DESC, idUSReferentiel, typeIntervention";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idIntervenantTag" => $idIntervenant,
            );
            $pdoStatement->execute($values);

            return $pdoStatement->fetchAll() ?? [];
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors de la recherche dans la base de données.");
        }
    }

    /**
     *
     * @param $idIntervenant
     * @param $annee
     * @return array
     */
    public function recupererVueParIdIntervenantAnnuel($idIntervenant, $annee): array
    {
        try {
            $sql = "SELECT *
            FROM vueInfosService WHERE idIntervenant=:idIntervenantTag AND millesime = $annee
            ORDER BY idUSReferentiel, typeIntervention";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idIntervenantTag" => $idIntervenant,
            );
            $pdoStatement->execute($values);

            return $pdoStatement->fetchAll() ?? [];
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors de la recherche dans la base de données.");
        }
    }

    public function recupererParIdUSA(int $idUniteServiceAnnee)
    {
        try {
            $sql = "SELECT * FROM DeclarationService WHERE idUniteServiceAnnee=:idUniteServiceAnneeTag";

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

    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new DeclarationService(
            $objetFormatTableau["idDeclarationService"],
            $objetFormatTableau["idIntervenant"],
            $objetFormatTableau["idUniteServiceAnnee"],
            $objetFormatTableau["mode"],
            $objetFormatTableau["idIntervention"]
        );
    }

    public function recupererParIdIntervenant(int $idIntervenant)
    {
        try {
            $sql = "SELECT * FROM DeclarationService WHERE idIntervenant =:idIntervenantTag";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "idIntervenantTag" => $idIntervenant,
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

    /**
     * @inheritDoc
     */
    protected function getNomTable(): string
    {
        return "DeclarationService";
    }

    protected function getNomsColonnes(): array
    {
        return ["idDeclarationService", "idIntervenant", "idUniteServiceAnnee", "mode", "idIntervention"];
    }

    protected function getNomClePrimaire(): string
    {
        return "idDeclarationService";
    }
}