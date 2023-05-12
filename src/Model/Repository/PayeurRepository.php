<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Payeur;

class PayeurRepository extends AbstractRepository
{

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