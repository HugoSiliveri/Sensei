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
        private ?string $libelle,
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
    public function getLibelle(): ?string
    {
        return $this->libelle;
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

    public function recupererFormatTableau(): array
    {
        return [
            "idUniteServiceAnnee" => $this->idUniteServiceAnnee,
            "idDepartement" => $this->idDepartement,
            "idUniteService" => $this->idUniteService,
            "libelle" => $this->libelle,
            "millesime" => $this->millesime,
            "heuresCM" => $this->heuresCM,
            "nbGroupesCM" => $this->nbGroupesCM,
            "heuresTD" => $this->heuresTD,
            "nbGroupesTD" => $this->nbGroupesTD,
            "heuresTP" => $this->heuresTP,
            "nbGroupesTP" => $this->nbGroupesTP,
            "heuresStage" => $this->heuresStage,
            "nbGroupesStage" => $this->nbGroupesStage,
            "heuresTerrain" => $this->heuresTerrain,
            "nbGroupesTerrain" => $this->nbGroupesTerrain,
            "validite" => $this->validite,
            "deleted" => $this->deleted
        ];
    }
}