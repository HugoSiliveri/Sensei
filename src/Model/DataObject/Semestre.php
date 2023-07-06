<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Semestre
 *
 * @tutorial Classe qui représente l'objet Semestre dans la base de données.
 *
 * @author Hugo Siliveri
 *
 */
class Semestre extends AbstractDataObject
{
    public function __construct(
        private int     $idSemestre,
        private ?string $libSemestre
    )
    {
    }

    /**
     * @return int
     */
    public function getIdSemestre(): int
    {
        return $this->idSemestre;
    }

    /**
     * @return string|null
     */
    public function getLibSemestre(): ?string
    {
        return $this->libSemestre;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idSemestreTag" => $this->idSemestre,
            "libSemestreTag" => $this->libSemestre
        ];
    }
}