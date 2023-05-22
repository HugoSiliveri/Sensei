<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Payeur;
use PDOException;

class PayeurRepository extends AbstractRepository
{

    public function ajouterSansIdPayeur(array $payeur)
    {
        try {
            $sql = "INSERT INTO Payeur(libPayeur) VALUES (:libPayeurTag)";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "libPayeurTag" => $payeur["libPayeur"],
            );
            $pdoStatement->execute($values);

            return null;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Erreur lors d'insertion dans la base de données.");
        }
    }

    /**
     * @inheritDoc
     */
    protected function getNomTable(): string
    {
        return "Payeur";
    }

    protected function getNomsColonnes(): array
    {
        return ["idPayeur", "libPayeur"];
    }

    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Payeur(
            $objetFormatTableau["idPayeur"],
            $objetFormatTableau["libPayeur"]
        );
    }

    protected function getNomClePrimaire(): string
    {
        return "idPayeur";
    }
}