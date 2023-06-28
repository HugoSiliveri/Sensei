<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Saison;

/**
 * @name SaisonRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des saisons.
 *
 * @author Hugo Siliveri
 *
 */
class SaisonRepository extends AbstractRepository
{

    /**
     * @inheritDoc
     */
    protected function getNomTable(): string
    {
        return "Saison";
    }

    protected function getNomsColonnes(): array
    {
        return ["idSaison", "libSaison"];
    }

    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Saison(
            $objetFormatTableau["idSaison"],
            $objetFormatTableau["libSaison"]
        );
    }

    protected function getNomClePrimaire(): string
    {
        return "idSaison";
    }
}