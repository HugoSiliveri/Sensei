<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name ResponsableUS
 *
 * @tutorial Classe qui représente l'objet ResponsableUS dans la base de données.
 * L'objet ResponsableUS montre les différents responsables d'une unité service lors d'une année.
 *
 * @author Hugo Siliveri
 *
 */
class ResponsableUS extends AbstractDataObject
{

    public function __construct(
        private int $idIntervenant,
        private int $idUniteServiceAnnee
    )
    {
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
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idIntervenantTag" => $this->idIntervenant,
            "idUniteServiceAnneeTag" => $this->idUniteServiceAnnee
        ];
    }
}