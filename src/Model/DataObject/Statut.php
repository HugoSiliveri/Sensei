<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Statut
 *
 * @tutorial Classe qui représente l'objet Statut dans la base de données.
 * L'objet Statut représente le statut d'un intervenant avec son possible volume horaire total.
 *
 * @author Hugo Siliveri
 *
 */
class Statut extends AbstractDataObject
{

    public function __construct(
        private int    $idStatut,
        private string $libStatut,
        private ?float $nbHeures
    )
    {
    }

    /**
     * @return int
     */
    public function getIdStatut(): int
    {
        return $this->idStatut;
    }

    /**
     * @return string
     */
    public function getLibStatut(): string
    {
        return $this->libStatut;
    }

    /**
     * @return float|null
     */
    public function getNbHeures(): ?float
    {
        return $this->nbHeures;
    }

    /**
     * @inheritDoc
     */
    public function recupererFormatTableau(): array
    {
        return [
            "idStatut" => $this->idStatut,
            "libStatut" => $this->libStatut,
            "nbHeures" => $this->nbHeures
        ];
    }
}