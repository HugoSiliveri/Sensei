<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Departement
 *
 * @tutorial Classe qui représente l'objet Departement dans la base de données.
 * L'objet Departement représente un département appartenant à une composante
 *
 * @example
 * Le département de mathématiques de la Faculté des Sciences est un département.
 * Il est différent d'un département de mathématiques d'une autre composante
 *
 * @author Hugo Siliveri
 *
 */
class Departement extends AbstractDataObject
{

    public function __construct(
        private int     $idDepartement,
        private ?string $libDepartement,
        private ?string $codeLettre,
        private ?int    $reportMax,
        private int     $idComposante,
        private int     $idEtat
    )
    {
    }

    /**
     * @return int
     */
    public function getIdDepartement(): int
    {
        return $this->idDepartement;
    }

    /**
     * @return string|null
     */
    public function getLibDepartement(): ?string
    {
        return $this->libDepartement;
    }

    /**
     * @return string|null
     */
    public function getCodeLettre(): ?string
    {
        return $this->codeLettre;
    }

    /**
     * @return int|null
     */
    public function getReportMax(): ?int
    {
        return $this->reportMax;
    }

    /**
     * @return int
     */
    public function getIdComposante(): int
    {
        return $this->idComposante;
    }

    /**
     * @return int
     */
    public function getIdEtat(): int
    {
        return $this->idEtat;
    }

    /**
     * @inheritDoc
     */
    public function recupererFormatTableau(): array
    {
        return [
            "idDepartement" => $this->idDepartement,
            "libDepartement" => $this->libDepartement,
            "codeLettre" => $this->codeLettre,
            "reportMax" => $this->reportMax,
            "idComposante" => $this->idComposante,
            "idEtat" => $this->idEtat
        ];
    }
}