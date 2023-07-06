<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Semestre;

/**
 * @name SemestreRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des semestres.
 *
 * @author Hugo Siliveri
 *
 */
class SemestreRepository extends AbstractRepository
{

    /**
     * @inheritDoc
     */
    protected function getNomTable(): string
    {
        return "Semestre";
    }

    protected function getNomsColonnes(): array
    {
        return ["idSemestre", "libSemestre"];
    }

    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Semestre(
            $objetFormatTableau["idSemestre"],
            $objetFormatTableau["libSemestre"]
        );
    }

    protected function getNomClePrimaire(): string
    {
        return "idSemestre";
    }
}