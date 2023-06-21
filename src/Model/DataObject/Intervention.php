<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Intervention
 *
 * @example
 * Une intervention I1 peut être une UE à être enseigné pendant un volume horaire de 30h dans un groupe particulier
 *
 * @author Hugo Siliveri
 *
 */
class Intervention extends AbstractDataObject
{

    public function __construct(
        private int     $idIntervention,
        private ?string $typeIntervention,
        private ?int    $numeroGroupeIntervention,
        private ?float  $volumeHoraire
    )
    {
    }

    /**
     * @return int
     */
    public function getIdIntervention(): int
    {
        return $this->idIntervention;
    }

    /**
     * @return string|null
     */
    public function getTypeIntervention(): ?string
    {
        return $this->typeIntervention;
    }

    /**
     * @return int|null
     */
    public function getNumeroGroupeIntervention(): ?int
    {
        return $this->numeroGroupeIntervention;
    }

    /**
     * @return float|null
     */
    public function getVolumeHoraire(): ?float
    {
        return $this->volumeHoraire;
    }

    /**
     * @param int $idIntervention
     */
    public function setIdIntervention(int $idIntervention): void
    {
        $this->idIntervention = $idIntervention;
    }

    /**
     * @param string|null $typeIntervention
     */
    public function setTypeIntervention(?string $typeIntervention): void
    {
        $this->typeIntervention = $typeIntervention;
    }

    /**
     * @param int|null $numeroGroupeIntervention
     */
    public function setNumeroGroupeIntervention(?int $numeroGroupeIntervention): void
    {
        $this->numeroGroupeIntervention = $numeroGroupeIntervention;
    }

    /**
     * @param float|null $volumeHoraire
     */
    public function setVolumeHoraire(?float $volumeHoraire): void
    {
        $this->volumeHoraire = $volumeHoraire;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idInterventionTag" => $this->idIntervention,
            "typeInterventionTag" => $this->typeIntervention,
            "numeroGroupeInterventionTag" => $this->numeroGroupeIntervention,
            "volumeHoraireTag" => $this->volumeHoraire
        ];
    }
}