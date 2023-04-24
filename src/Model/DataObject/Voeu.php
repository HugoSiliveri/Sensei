<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Voeu
 *
 * @tutorial Classe qui représente l'objet Voeu dans la base de données.
 * L'objet Voeu est une demande formulée par un intervenant pour une intervention dans une unité service spécifique
 * d'une année
 *
 * @author Hugo Siliveri
 *
 */
class Voeu extends AbstractDataObject
{

    public function __construct(
        private int $idVoeu,
        private int $idIntervenant,
        private int $idUniteServiceAnnee,
        private int $idIntervention
    )
    {
    }

    /**
     * @return int
     */
    public function getIdVoeu(): int
    {
        return $this->idVoeu;
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
    public function getIdUniteServiceAnnee(): int
    {
        return $this->idUniteServiceAnnee;
    }

    /**
     * @return int
     */
    public function getIdIntervention(): int
    {
        return $this->idIntervention;
    }

    public function recupererFormatTableau(): array
    {
        return [
            "idVoeu" => $this->idVoeu,
            "idIntervenant" => $this->idIntervenant,
            "idUniteServiceAnnee" => $this->idUniteServiceAnnee,
            "idIntervention" => $this->idIntervention
        ];
    }

}