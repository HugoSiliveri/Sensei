<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Nature;
use PDOException;

class NatureRepository extends AbstractRepository
{

    public function ajouterSansIdNature(array $nature){
        try {
            $sql = "INSERT INTO Nature(libNature) VALUES (:libNatureTag)";

            $pdoStatement = parent::getConnexionBaseDeDonnees()->getPdo()->prepare($sql);

            $values = array(
                "libNatureTag" => $nature["libNature"],
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
        return "Nature";
    }

    protected function getNomsColonnes(): array
    {
        return ["idNature", "libNature"];
    }

    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Nature(
            $objetFormatTableau["idNature"],
            $objetFormatTableau["libNature"]
        );
    }

    protected function getNomClePrimaire(): string
    {
        return "idNature";
    }
}