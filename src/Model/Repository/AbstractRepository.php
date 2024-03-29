<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use PDOException;

/**
 * @name AbstractRepository
 *
 * @tutorial Classe qui regroupe toutes les méthodes communes aux classes gérant la persistance des données
 * (répertoire Repository).
 *
 * @author Hugo Siliveri
 *
 */
abstract class AbstractRepository implements AbstractRepositoryInterface
{
    public function __construct(private readonly ConnexionBaseDeDonneesInterface $connexionBaseDeDonnees)
    {
    }

    /**
     * @inheritDoc
     */
    public function recuperer($limit = 200): array
    {
        $nomTable = $this->getNomTable();
        $champsSelect = implode(", ", $this->getNomsColonnes());
        $requeteSQL = <<<SQL
        SELECT $champsSelect FROM $nomTable LIMIT $limit;
        SQL;

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->query($requeteSQL);

        $objets = [];
        foreach ($pdoStatement as $objetFormatTableau) {
            $objets[] = $this->construireDepuisTableau($objetFormatTableau);
        }
        return $objets;
    }

    /**
     * Ci-dessous sont regroupées toutes les méthodes à redéfinir dans les sous-classes d'AbstractRepository.
     */
    protected abstract function getNomTable(): string;

    protected abstract function getNomsColonnes(): array;

    protected abstract function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject;

    /**
     * @inheritDoc
     */
    public function recupererParClePrimaire(string $valeurClePrimaire): ?AbstractDataObject
    {
        $nomTable = $this->getNomTable();
        $nomClePrimaire = $this->getNomClePrimaire();
        $sql = "SELECT * from $nomTable WHERE $nomClePrimaire=:clePrimaireTag";

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($sql);

        $values = array(
            "clePrimaireTag" => $valeurClePrimaire,
        );
        $pdoStatement->execute($values);

        $objetFormatTableau = $pdoStatement->fetch();

        if ($objetFormatTableau !== false) {
            return $this->construireDepuisTableau($objetFormatTableau);
        }
        return null;
    }

    protected abstract function getNomClePrimaire(): string;

    /**
     * @inheritDoc
     */
    public function supprimer(string $valeurClePrimaire): void
    {
        $nomTable = $this->getNomTable();
        $nomClePrimaire = $this->getNomClePrimaire();
        $sql = "DELETE FROM $nomTable WHERE $nomClePrimaire= :clePrimaireTag;";

        $pdoStatement = $this->connexionBaseDeDonnees->getPDO()->prepare($sql);

        $values = array(
            "clePrimaireTag" => $valeurClePrimaire
        );

        $pdoStatement->execute($values);
    }

    /**
     * @inheritDoc
     */
    public function mettreAJour(AbstractDataObject $object): void
    {
        $nomTable = $this->getNomTable();
        $nomClePrimaire = $this->getNomClePrimaire();
        $nomsColonnes = $this->getNomsColonnes();

        $partiesSet = array_map(function ($nomcolonne) {
            return "$nomcolonne = :{$nomcolonne}Tag";
        }, $nomsColonnes);
        $setString = join(',', $partiesSet);
        $whereString = "$nomClePrimaire = :{$nomClePrimaire}Tag";

        $sql = "UPDATE $nomTable SET $setString WHERE $whereString";
        $req_prep = $this->connexionBaseDeDonnees->getPDO()->prepare($sql);

        $objetFormatTableau = $object->exporterEnFormatRequetePreparee();

        $req_prep->execute($objetFormatTableau);

    }

    /**
     * @inheritDoc
     */
    public function ajouter(AbstractDataObject $object): bool
    {
        $nomTable = $this->getNomTable();
        $nomsColonnes = $this->getNomsColonnes();

        $insertString = '(' . join(', ', $nomsColonnes) . ')';

        $partiesValues = array_map(function ($nomcolonne) {
            return ":{$nomcolonne}_tag";
        }, $nomsColonnes);
        $valueString = '(' . join(', ', $partiesValues) . ')';

        $sql = "INSERT INTO $nomTable $insertString VALUES $valueString";

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($sql);

        $objetFormatTableau = $object->exporterEnFormatRequetePreparee();

        try {
            $pdoStatement->execute($objetFormatTableau);
            return true;
        } catch (PDOException $exception) {
            if ($pdoStatement->errorCode() === "23000") {
                // Je ne traite que l'erreur "Duplicate entry"
                return false;
            } else {
                // Pour les autres erreurs, je transmets l'exception
                throw $exception;
            }
        }
    }

    /**
     * @return ConnexionBaseDeDonneesInterface
     */
    public function getConnexionBaseDeDonnees(): ConnexionBaseDeDonneesInterface
    {
        return $this->connexionBaseDeDonnees;
    }

}