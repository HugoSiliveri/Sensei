<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Coloration
 *
 * @tutorial Classe qui représente l'objet Coloration dans la base de données.
 * L'objet Coloration représente l'usage d'une unité service dans une année particulière par un département
 *
 * @example
 * U1 représente une UniteServiceAnnee
 * D1 et D2 sont des départements
 * U1 peut appartenir à D1 mais peut être enseignée par D2 -> Ceci est la coloration.
 *
 * @author Hugo Siliveri
 *
 */
class Coloration extends AbstractDataObject
{

    public function __construct(
        private int $idDepartement,
        private int $idUniteServiceAnnee
    )
    {
    }

    /**
     * @return int
     */
    public function getIdDepartement(): int
    {
        return $this->idDepartement;
    }

    /**
     * @return int
     */
    public function getIdUniteServiceAnnee(): int
    {
        return $this->idUniteServiceAnnee;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idDepartementTag" => $this->idDepartement,
            "idUniteServiceAnneeTag" => $this->idUniteServiceAnnee
        ];
    }
}