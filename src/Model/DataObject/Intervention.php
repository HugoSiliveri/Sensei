<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Intervention
 *
 * @tutorial Classe qui représente l'objet Intervention dans la base de données.
 * L'objet Intervention correspond aux enseignements présents dans la liste des vœux pour les enseignants.
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
        private int     $idIntervenant,
        private ?string $typeIntervention,
        private ?int    $numeroGroupeIntervention,
        private ?float  $volumeHoraire
    )
    {
    }

    /**
     * @return int
     */
    public function getIdIntervenant(): int
    {
        return $this->idIntervenant;
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

    public function recupererFormatTableau(): array
    {
        return [
            "idIntervenant" => $this->idIntervenant,
            "typeIntervention" => $this->typeIntervention,
            "numeroGroupeIntervention" => $this->numeroGroupeIntervention,
            "volumeHoraire" => $this->volumeHoraire
        ];
    }
}