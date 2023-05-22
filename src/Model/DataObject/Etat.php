<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Etat
 *
 * @tutorial Classe qui représente l'objet Etat dans la base de données.
 * L'objet Etat représente les différents types d'états d'un département
 *
 * @example
 * NORMAL = Fonctionnement normal de l'application
 * VOEUX = Phase de formulations des vœux. Certaines fonctionnalités apparaissent lors de cette phase
 * CHANTIER = Phase de délibération. Les vœux ne peuvent plus être modifiés/supprimés/rajoutés pendant cette phase
 *
 * @author Hugo Siliveri
 *
 */
class Etat extends AbstractDataObject
{

    public function __construct(
        private int    $idEtat,
        private string $libEtat
    )
    {
    }

    /**
     * @return int
     */
    public function getIdEtat(): int
    {
        return $this->idEtat;
    }

    /**
     * @return string
     */
    public function getLibEtat(): string
    {
        return $this->libEtat;
    }

    /**
     * @param int $idEtat
     */
    public function setIdEtat(int $idEtat): void
    {
        $this->idEtat = $idEtat;
    }

    /**
     * @param string $libEtat
     */
    public function setLibEtat(string $libEtat): void
    {
        $this->libEtat = $libEtat;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idEtatTag" => $this->idEtat,
            "libEtatTag" => $this->libEtat
        ];
    }
}