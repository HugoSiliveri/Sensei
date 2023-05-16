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
     * @param int $idDroit
     */
    public function setIdDroit(int $idDroit): void
    {
        $this->idDroit = $idDroit;
    }

    /**
     * @param string $typeDroit
     */
    public function setTypeDroit(string $typeDroit): void
    {
        $this->typeDroit = $typeDroit;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idDroitTag" => $this->idDroit,
            "typeDroitTag" => $this->typeDroit
        ];
    }
}