<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Emploi
 *
 * @tutorial Classe qui représente l'objet Emploi dans la base de données.
 * L'objet Emploi représente les différents types d'emploi qu'un intervenant peut posséder lors d'un service annuel
 *
 * @example
 * Un intervenant I1 peut être vacataire au service annuel S1
 * mais peut être gestionnaire au service annuel S2
 *
 * @author Hugo Siliveri
 *
 */
class Emploi extends AbstractDataObject
{

    public function __construct(
        private int     $idEmploi,
        private ?string $libEmploi
    )
    {
    }

    /**
     * @return int
     */
    public function getIdEmploi(): int
    {
        return $this->idEmploi;
    }

    /**
     * @return string|null
     */
    public function getLibEmploi(): ?string
    {
        return $this->libEmploi;
    }

    public function recupererFormatTableau(): array
    {
        return [
            "idEmploi" => $this->idEmploi,
            "libEmploi" => $this->libEmploi
        ];
    }
}