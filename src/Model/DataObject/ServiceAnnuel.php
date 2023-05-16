<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name ServiceAnnuel
 *
 * @tutorial Classe qui représente l'objet ServiceAnnuel dans la base de données.
 * L'objet ServiceAnnuel représente le travail réalisé par un intervenant dans un département dans une année
 *
 * @author Hugo Siliveri
 *
 */
class ServiceAnnuel extends AbstractDataObject
{

    public function __construct(
        private int   $idServiceAnnuel,
        private ?int  $idDepartement,
        private int   $idIntervenant,
        private int   $millesime,
        private int   $idEmploi,
        private float $serviceStatuaire,
        private float $serviceFait,
        private float $delta,
        private int   $deleted
    )
    {
    }

    /**
     * @return int
     */
    public function getIdServiceAnnuel(): int
    {
        return $this->idServiceAnnuel;
    }

    /**
     * @return int|null
     */
    public function getIdDepartement(): ?int
    {
        return $this->idDepartement;
    }

    /**
     * @return int
     */
    public function getIdIntervenant(): int
    {
        return $this->idIntervenant;
    }

    /**
     * @return int
     */
    public function getMillesime(): int
    {
        return $this->millesime;
    }

    /**
     * @return int
     */
    public function getIdEmploi(): int
    {
        return $this->idEmploi;
    }

    /**
     * @return float
     */
    public function getServiceStatuaire(): float
    {
        return $this->serviceStatuaire;
    }

    /**
     * @return float
     */
    public function getServiceFait(): float
    {
        return $this->serviceFait;
    }

    /**
     * @return float
     */
    public function getDelta(): float
    {
        return $this->delta;
    }

    /**
     * @return int
     */
    public function getDeleted(): int
    {
        return $this->deleted;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idServiceAnnuelTag" => $this->idServiceAnnuel,
            "idDepartmentTag" => $this->idDepartement,
            "idIntervenantTag" => $this->idIntervenant,
            "millesimeTag" => $this->millesime,
            "idEmploiTag" => $this->idEmploi,
            "serviceStatuaireTag" => $this->serviceStatuaire,
            "serviceFaitTag" => $this->serviceFait,
            "deltaTag" => $this->delta,
            "deletedTag" => $this->deleted
        ];
    }
}