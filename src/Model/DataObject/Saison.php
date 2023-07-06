<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Saison
 *
 * @tutorial Classe qui représente l'objet Saison dans la base de données.
 *
 * @author Hugo Siliveri
 *
 */
class Saison extends AbstractDataObject
{
    public function __construct(
        private int     $idSaison,
        private ?string $libSaison
    )
    {
    }

    /**
     * @return int
     */
    public function getIdSaison(): int
    {
        return $this->idSaison;
    }

    /**
     * @return string|null
     */
    public function getLibSaison(): ?string
    {
        return $this->libSaison;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idSaisonTag" => $this->idSaison,
            "libSaisonTag" => $this->libSaison
        ];
    }
}