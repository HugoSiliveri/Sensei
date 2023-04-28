<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name UniteService
 *
 * @tutorial Classe qui représente l'objet UniteService dans la base de données.
 * L'objet UniteService correspond aux différentes unités de services (UE, UR, DE, HF) existantes dans l'UM
 *
 * @author Hugo Siliveri
 *
 */
class UniteService extends AbstractDataObject
{

    public function __construct(
        private int     $idUniteService,
        private ?string    $idUSReferentiel,
        private ?string $libUS,
        private ?string  $nature,
        private ?int    $ancetre,
        private ?int    $anneeOuverture,
        private ?int    $anneeCloture,
        private ?float  $ECTS,
        private float   $heuresCM,
        private float   $heuresTD,
        private float   $heuresTP,
        private float   $heuresStage,
        private float   $heuresTerrain,
        private int     $semestre,
        private int     $saison,
        private ?string $payeur,
        private ?int     $validite,
        private ?int     $deleted
    )
    {
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
    public function getIdUSReferentiel(): ?string
    {
        return $this->idUSReferentiel;
    }

    /**
     * @return string|null
     */
    public function getLibUS(): ?string
    {
        return $this->libUS;
    }

    /**
     * @return string|null
     */
    public function getNature(): ?string
    {
        return $this->nature;
    }

    /**
     * @return int|null
     */
    public function getAncetre(): ?int
    {
        return $this->ancetre;
    }

    /**
     * @return int|null
     */
    public function getAnneeOuverture(): ?int
    {
        return $this->anneeOuverture;
    }

    /**
     * @return int|null
     */
    public function getAnneeCloture(): ?int
    {
        return $this->anneeCloture;
    }

    /**
     * @return float|null
     */
    public function getECTS(): ?float
    {
        return $this->ECTS;
    }

    /**
     * @return float
     */
    public function getHeuresCM(): float
    {
        return $this->heuresCM;
    }

    /**
     * @return float
     */
    public function getHeuresTD(): float
    {
        return $this->heuresTD;
    }

    /**
     * @return float
     */
    public function getHeuresTP(): float
    {
        return $this->heuresTP;
    }

    /**
     * @return float
     */
    public function getHeuresStage(): float
    {
        return $this->heuresStage;
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
    public function getSemestre(): int
    {
        return $this->semestre;
    }

    /**
     * @return int
     */
    public function getSaison(): int
    {
        return $this->saison;
    }

    /**
     * @return string|null
     */
    public function getPayeur(): ?string
    {
        return $this->payeur;
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
     * @inheritDoc
     */
    public function recupererFormatTableau(): array
    {
        return [
            "idUniteService" => $this->idUniteService,
            "idUSReferentiel" => $this->idUSReferentiel,
            "libUS" => $this->libUS,
            "nature" => $this->nature,
            "ancetre" => $this->ancetre,
            "anneeOuverture" => $this->anneeOuverture,
            "anneeCloture" => $this->anneeCloture,
            "ECTS" => $this->ECTS,
            "heuresCM" => $this->heuresCM,
            "heuresTD" => $this->heuresTD,
            "heuresTP" => $this->heuresTP,
            "heuresStage" => $this->heuresStage,
            "heuresTerrain" => $this->heuresTerrain,
            "semestre" => $this->semestre,
            "saison" => $this->saison,
            "payeur" => $this->payeur,
            "validite" => $this->validite,
            "deleted" => $this->deleted,
        ];
    }
}