<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Etat;

class EtatRepository extends AbstractRepository
{

    /**
     * @inheritDoc
     */
    protected function getNomTable(): string
    {
        return "Etat";
    }

    protected function getNomsColonnes(): array
    {
        return ["idEtat", "libEtat"];
    }

    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Etat(
            $objetFormatTableau["idEtat"],
            $objetFormatTableau["libEtat"]
        );
    }

    protected function getNomClePrimaire(): string
    {
        return "idEtat";
    }
}