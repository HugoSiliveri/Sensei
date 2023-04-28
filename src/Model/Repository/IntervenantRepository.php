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

    /**
     * Retourne l'intervenant qui possède le même idIntervenantReferentiel que celui en paramètre.
     * Retourne null s'il ne trouve pas
     *
     * @param $idIntervenantReferentiel
     * @return AbstractDataObject|null
     */
    public function recupererParUID($idIntervenantReferentiel): ?AbstractDataObject{
        $sql = "SELECT * from Intervenant WHERE idIntervenantReferentiel=:uidTag";

        $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

        $values = array(
            "clePrimaireTag" => $$idIntervenantReferentiel,
        );
        $pdoStatement->execute($values);

        $objetFormatTableau = $pdoStatement->fetch();

        if ($objetFormatTableau !== false) {
            return $this->construireDepuisTableau($objetFormatTableau);
        }
        return null;
    }

    /**
     * Retourne l'intervenant qui possède le même emailInstitutionnel que celui en paramètre.
     * Retourne null s'il ne trouve pas
     *
     * @param $emailInstitutionnel
     * @return AbstractDataObject|null
     */
    public function recupererParEmailInstitutionnel($emailInstitutionnel): ?AbstractDataObject{
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
    }

    public function recupererPourAutoCompletion(array $intervenantArray, $limit = 5): array {
        try {
            $sql = "SELECT * from Intervenant 
             WHERE nom LIKE :nomTag OR prenom LIKE :prenomTag OR idIntervenantReferentiel LIKE :idIntervenantReferentielTag
             ORDER BY nom, prenom, idIntervenantReferentiel
             LIMIT $limit";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "nomTag" => $intervenantArray["nom"] . "%",
                "prenomTag" => $intervenantArray["prenom"] . "%",
                "idIntervenantReferentielTag" => $intervenantArray["idIntervenantReferentiel"] . "%"
            );
            $pdoStatement->execute($values);
            $pdoStatement->setFetchMode(PDO::FETCH_OBJ);
            return $pdoStatement->fetchAll();

        } catch (PDOException $exception){
            echo $exception->getMessage();
            die("Erreur lors de la recherche dans la base de données.");
        }

    }

    /**
     * Construit un objet Intervenant à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Intervenant
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
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
}