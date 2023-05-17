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
     * @param int $idUniteServiceAnnee
     */
    public function setIdUniteServiceAnnee(int $idUniteServiceAnnee): void
    {
        $this->idUniteServiceAnnee = $idUniteServiceAnnee;
    }

    /**
     * @param int|null $idDepartement
     */
    public function setIdDepartement(?int $idDepartement): void
    {
        $this->idDepartement = $idDepartement;
    }

    /**
     * @param int $idUniteService
     */
    public function setIdUniteService(int $idUniteService): void
    {
        $this->idUniteService = $idUniteService;
    }

    /**
     * @param string|null $libUSA
     */
    public function setLibUSA(?string $libUSA): void
    {
        $this->libUSA = $libUSA;
    }

    /**
     * @param int|null $millesime
     */
    public function setMillesime(?int $millesime): void
    {
        $this->millesime = $millesime;
    }

    /**
     * @param float $heuresCM
     */
    public function setHeuresCM(float $heuresCM): void
    {
        $this->heuresCM = $heuresCM;
    }

    /**
     * @param int $nbGroupesCM
     */
    public function setNbGroupesCM(int $nbGroupesCM): void
    {
        $this->nbGroupesCM = $nbGroupesCM;
    }

    /**
     * @param float $heuresTD
     */
    public function setHeuresTD(float $heuresTD): void
    {
        $this->heuresTD = $heuresTD;
    }

    /**
     * @param int $nbGroupesTD
     */
    public function setNbGroupesTD(int $nbGroupesTD): void
    {
        $this->nbGroupesTD = $nbGroupesTD;
    }

    /**
     * @param float $heuresTP
     */
    public function setHeuresTP(float $heuresTP): void
    {
        $this->heuresTP = $heuresTP;
    }

    /**
     * @param int $nbGroupesTP
     */
    public function setNbGroupesTP(int $nbGroupesTP): void
    {
        $this->nbGroupesTP = $nbGroupesTP;
    }

    /**
     * @param float $heuresStage
     */
    public function setHeuresStage(float $heuresStage): void
    {
        $this->heuresStage = $heuresStage;
    }

    /**
     * @param int $nbGroupesStage
     */
    public function setNbGroupesStage(int $nbGroupesStage): void
    {
        $this->nbGroupesStage = $nbGroupesStage;
    }

    /**
     * @param float $heuresTerrain
     */
    public function setHeuresTerrain(float $heuresTerrain): void
    {
        $this->heuresTerrain = $heuresTerrain;
    }

    /**
     * @param int $nbGroupesTerrain
     */
    public function setNbGroupesTerrain(int $nbGroupesTerrain): void
    {
        $this->nbGroupesTerrain = $nbGroupesTerrain;
    }

    /**
     * @param float $heuresInnovationPedagogique
     */
    public function setHeuresInnovationPedagogique(float $heuresInnovationPedagogique): void
    {
        $this->heuresInnovationPedagogique = $heuresInnovationPedagogique;
    }

    /**
     * @param int $nbGroupesInnovationPedagogique
     */
    public function setNbGroupesInnovationPedagogique(int $nbGroupesInnovationPedagogique): void
    {
        $this->nbGroupesInnovationPedagogique = $nbGroupesInnovationPedagogique;
    }

    /**
     * @param int|null $validite
     */
    public function setValidite(?int $validite): void
    {
        $this->validite = $validite;
    }

    /**
     * @param int|null $deleted
     */
    public function setDeleted(?int $deleted): void
    {
        $this->deleted = $deleted;
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
            "libUSATag" => $this->libUSA,
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
            "nbGroupesInnovationPedagogiqueTag" => $this->nbGroupesInnovationPedagogique,
            "validiteTag" => $this->validite,
            "deletedTag" => $this->deleted
        ];
    }
}