<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name UniteServiceAnnee
 *
 * @tutorial Classe qui représente l'objet UniteServiceAnnee dans la base de données.
 * L'objet UniteServiceAnnee est une UniteService d'une année précise. Les unités services peuvent être légèrement
 * modifiées lors d'une année.
 *
 * @author Hugo Siliveri
 *
 */
class UniteServiceAnnee extends AbstractDataObject
{

    public function __construct(
        private int     $idUniteServiceAnnee,
        private ?int    $idDepartement,
        private int     $idUniteService,
        private ?string $libUSA,
        private ?int    $millesime,
        private float   $heuresCM,
        private int     $nbGroupesCM,
        private float   $heuresTD,
        private int     $nbGroupesTD,
        private float   $heuresTP,
        private int     $nbGroupesTP,
        private float   $heuresStage,
        private int     $nbGroupesStage,
        private float   $heuresTerrain,
        private int     $nbGroupesTerrain,
        private float   $heuresInnovationPedagogique,
        private int     $nbGroupesInnovationPedagogique,
        private ?int    $validite,
        private ?int    $deleted
    )
    {
    }

    /**
     * @return int
     */
    public function getIdUniteServiceAnnee(): int
    {
        return $this->idUniteServiceAnnee;
    }

    /**
     * @return int|null
     */
    public function getIdDepartement(): ?int
    {
        return $this->idDepartement;
    }

    /**
     * @return int
     */
    public function getIdUniteService(): int
    {
        return $this->idUniteService;
    }

    /**
     * @return string|null
     */
    public function getLibUSA(): ?string
    {
        return $this->libUSA;
    }

    /**
     * @return int|null
     */
    public function getMillesime(): ?int
    {
        return $this->millesime;
    }

    /**
     * @return float
     */
    public function getHeuresCM(): float
    {
        return $this->heuresCM;
    }

    /**
     * @return int
     */
    public function getNbGroupesCM(): int
    {
        return $this->nbGroupesCM;
    }

    /**
     * @return float
     */
    public function getHeuresTD(): float
    {
        return $this->heuresTD;
    }

    /**
     * @return int
     */
    public function getNbGroupesTD(): int
    {
        return $this->nbGroupesTD;
    }

    /**
     * @return float
     */
    public function getHeuresTP(): float
    {
        return $this->heuresTP;
    }

    /**
     * @return int
     */
    public function getNbGroupesTP(): int
    {
        return $this->nbGroupesTP;
    }

    /**
     * @return float
     */
    public function getHeuresStage(): float
    {
        return $this->heuresStage;
    }

    /**
     * @return int
     */
    public function getNbGroupesStage(): int
    {
        return $this->nbGroupesStage;
    }

    /**
     * @return float
     */
    public function getHeuresTerrain(): float
    {
        return $this->heuresTerrain;
    }

    /**
     * @return int
     */
    public function getNbGroupesTerrain(): int
    {
        return $this->nbGroupesTerrain;
    }

    /**
     * @return int|null
     */
    public function getValidite(): ?int
    {
        return $this->validite;
    }

    /**
     * @return int|null
     */
    public function getDeleted(): ?int
    {
        return $this->deleted;
    }

    /**
     * @return float
     */
    public function getHeuresInnovationPedagogique(): float
    {
        return $this->heuresInnovationPedagogique;
    }

    /**
     * @return int
     */
    public function getNbGroupesInnovationPedagogique(): int
    {
        return $this->nbGroupesInnovationPedagogique;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idUniteServiceAnneeTag" => $this->idUniteServiceAnnee,
            "idDepartementTag" => $this->idDepartement,
            "idUniteServiceTag" => $this->idUniteService,
            "libelleTag" => $this->libUSA,
            "millesimeTag" => $this->millesime,
            "heuresCMTag" => $this->heuresCM,
            "nbGroupesCMTag" => $this->nbGroupesCM,
            "heuresTDTag" => $this->heuresTD,
            "nbGroupesTDTag" => $this->nbGroupesTD,
            "heuresTPTag" => $this->heuresTP,
            "nbGroupesTPTag" => $this->nbGroupesTP,
            "heuresStageTag" => $this->heuresStage,
            "nbGroupesStageTag" => $this->nbGroupesStage,
            "heuresTerrainTag" => $this->heuresTerrain,
            "nbGroupesTerrainTag" => $this->nbGroupesTerrain,
            "heuresInnovationPedagogiqueTag" => $this->heuresInnovationPedagogique,
            "nbGroupesInnovationPedahogiqueTag" => $this->nbGroupesInnovationPedagogique,
            "validiteTag" => $this->validite,
            "deletedTag" => $this->deleted
        ];
    }
}