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
        private ?string $idUSReferentiel,
        private ?string $libUS,
        private ?string $nature,
        private ?int    $ancetre,
        private ?int    $anneeOuverture,
        private ?int    $anneeCloture,
        private ?float  $ECTS,
        private float   $heuresCM,
        private float   $heuresTD,
        private float   $heuresTP,
        private float   $heuresStage,
        private float   $heuresTerrain,
        private float   $heuresInnovationPedagogique,
        private int     $semestre,
        private int     $saison,
        private int     $idPayeur,
        private ?int    $validite,
        private ?int    $deleted
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
    public function getIdPayeur(): ?string
    {
        return $this->idPayeur;
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
     * @param int $idUniteService
     */
    public function setIdUniteService(int $idUniteService): void
    {
        $this->idUniteService = $idUniteService;
    }

    /**
     * @param string|null $idUSReferentiel
     */
    public function setIdUSReferentiel(?string $idUSReferentiel): void
    {
        $this->idUSReferentiel = $idUSReferentiel;
    }

    /**
     * @param string|null $libUS
     */
    public function setLibUS(?string $libUS): void
    {
        $this->libUS = $libUS;
    }

    /**
     * @param string|null $nature
     */
    public function setNature(?string $nature): void
    {
        $this->nature = $nature;
    }

    /**
     * @param int|null $ancetre
     */
    public function setAncetre(?int $ancetre): void
    {
        $this->ancetre = $ancetre;
    }

    /**
     * @param int|null $anneeOuverture
     */
    public function setAnneeOuverture(?int $anneeOuverture): void
    {
        $this->anneeOuverture = $anneeOuverture;
    }

    /**
     * @param int|null $anneeCloture
     */
    public function setAnneeCloture(?int $anneeCloture): void
    {
        $this->anneeCloture = $anneeCloture;
    }

    /**
     * @param float|null $ECTS
     */
    public function setECTS(?float $ECTS): void
    {
        $this->ECTS = $ECTS;
    }

    /**
     * @param float $heuresCM
     */
    public function setHeuresCM(float $heuresCM): void
    {
        $this->heuresCM = $heuresCM;
    }

    /**
     * @param float $heuresTD
     */
    public function setHeuresTD(float $heuresTD): void
    {
        $this->heuresTD = $heuresTD;
    }

    /**
     * @param float $heuresTP
     */
    public function setHeuresTP(float $heuresTP): void
    {
        $this->heuresTP = $heuresTP;
    }

    /**
     * @param float $heuresStage
     */
    public function setHeuresStage(float $heuresStage): void
    {
        $this->heuresStage = $heuresStage;
    }

    /**
     * @param float $heuresTerrain
     */
    public function setHeuresTerrain(float $heuresTerrain): void
    {
        $this->heuresTerrain = $heuresTerrain;
    }

    /**
     * @param float $heuresInnovationPedagogique
     */
    public function setHeuresInnovationPedagogique(float $heuresInnovationPedagogique): void
    {
        $this->heuresInnovationPedagogique = $heuresInnovationPedagogique;
    }

    /**
     * @param int $semestre
     */
    public function setSemestre(int $semestre): void
    {
        $this->semestre = $semestre;
    }

    /**
     * @param int $saison
     */
    public function setSaison(int $saison): void
    {
        $this->saison = $saison;
    }

    /**
     * @param int $idPayeur
     */
    public function setIdPayeur(int $idPayeur): void
    {
        $this->idPayeur = $idPayeur;
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
            "idUniteServiceTag" => $this->idUniteService,
            "idUSReferentielTag" => $this->idUSReferentiel,
            "libUSTag" => $this->libUS,
            "natureTag" => $this->nature,
            "ancetreTag" => $this->ancetre,
            "anneeOuvertureTag" => $this->anneeOuverture,
            "anneeClotureTag" => $this->anneeCloture,
            "ECTSTag" => $this->ECTS,
            "heuresCMTag" => $this->heuresCM,
            "heuresTDTag" => $this->heuresTD,
            "heuresTPTag" => $this->heuresTP,
            "heuresStageTag" => $this->heuresStage,
            "heuresTerrainTag" => $this->heuresTerrain,
            "heuresInnovationPedagogiqueTag" => $this->heuresInnovationPedagogique,
            "semestreTag" => $this->semestre,
            "saisonTag" => $this->saison,
            "idPayeurTag" => $this->idPayeur,
            "validiteTag" => $this->validite,
            "deletedTag" => $this->deleted,
        ];
    }
}