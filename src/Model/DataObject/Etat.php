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
     * @inheritDoc
     */
    public function recupererFormatTableau(): array
    {
        return [
            "idEtat" => $this->idEtat,
            "libEtat" => $this->libEtat
        ];
    }
}