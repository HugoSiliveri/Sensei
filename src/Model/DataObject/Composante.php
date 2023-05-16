<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Composante
 *
 * @tutorial Classe qui représente l'objet Composante dans la base de données.
 * L'objet Composante représente une entité appartenant à l'UM
 *
 * @example
 * La Faculté des Sciences est une composante de l'UM
 *
 * @author Hugo Siliveri
 *
 */
class Composante extends AbstractDataObject
{

    public function __construct(
        private int     $idComposante,
        private ?string $libComposante,
        private int     $anneeDeTravail,
        private int     $anneeDeValidation
    )
    {
    }

    /**
     * @return int
     */
    public function getIdComposante(): int
    {
        return $this->idComposante;
    }

    /**
     * @return string|null
     */
    public function getLibComposante(): ?string
    {
        return $this->libComposante;
    }

    /**
     * @return int
     */
    public function getAnneeDeTravail(): int
    {
        return $this->anneeDeTravail;
    }

    /**
     * @return int
     */
    public function getAnneeDeValidation(): int
    {
        return $this->anneeDeValidation;
    }

    /**
     * @param int $idComposante
     */
    public function setIdComposante(int $idComposante): void
    {
        $this->idComposante = $idComposante;
    }

    /**
     * @param string|null $libComposante
     */
    public function setLibComposante(?string $libComposante): void
    {
        $this->libComposante = $libComposante;
    }

    /**
     * @param int $anneeDeTravail
     */
    public function setAnneeDeTravail(int $anneeDeTravail): void
    {
        $this->anneeDeTravail = $anneeDeTravail;
    }

    /**
     * @param int $anneeDeValidation
     */
    public function setAnneeDeValidation(int $anneeDeValidation): void
    {
        $this->anneeDeValidation = $anneeDeValidation;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idComposanteTag" => $this->idComposante,
            "libComposanteTag" => $this->libComposante,
            "anneeDeTravailTag" => $this->anneeDeTravail,
            "anneeDeValidationTag" => $this->anneeDeValidation
        ];
    }
}