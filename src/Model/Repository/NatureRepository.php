<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Nature;

class NatureRepository extends AbstractRepository
{

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