<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Appartenir
 *
 * @tutorial Classe qui représente l'objet Appartenir dans la base de données.
 * L'objet Appartenir représente l'appartenance d'une unité service à un département
 *
 * @author Hugo Siliveri
 *
 */
class Appartenir extends AbstractDataObject
{

    public function __construct(
        private int  $idAppartenir,
        private ?int $idDepartement,
        private ?int $idUniteService
    )
    {
    }

    /**
     * @return int
     */
    public function getIdAppartenir(): int
    {
        return $this->idAppartenir;
    }

    /**
     * @return int|null
     */
    public function getIdDepartement(): ?int
    {
        return $this->idDepartement;
    }

    /**
     * @return int|null
     */
    public function getIdUniteService(): ?int
    {
        return $this->idUniteService;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idAppartenirTag" => $this->idAppartenir,
            "idDepartementTag" => $this->idDepartement,
            "idUniteServiceTag" => $this->idUniteService
        ];
    }

}