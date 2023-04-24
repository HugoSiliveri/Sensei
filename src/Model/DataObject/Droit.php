<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Droit
 *
 * @tutorial Classe qui représente l'objet Droit dans la base de données.
 * L'objet Droit correspond aux différents types de droits utilisateurs.
 *
 * @author Hugo Siliveri
 *
 */
class Droit extends AbstractDataObject
{

    public function __construct(
        private int    $idDroit,
        private string $typeDroit
    )
    {
    }

    /**
     * @return int
     */
    public function getIdDroit(): int
    {
        return $this->idDroit;
    }

    /**
     * @return string
     */
    public function getTypeDroit(): string
    {
        return $this->typeDroit;
    }

    /**
     * @inheritDoc
     */
    public function recupererFormatTableau(): array
    {
        return [
            "idDroit" => $this->idDroit,
            "typeDroit" => $this->typeDroit
        ];
    }
}