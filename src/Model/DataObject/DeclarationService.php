<?php

namespace App\Sensei\Model\DataObject;

class DeclarationService extends AbstractDataObject
{
    public function __construct(
        private int $idDeclarationService,
        private int $idIntervenant,
        private int $idUniteServiceAnnee,
        private ?string $mode,
        private int $idIntervention,
    )
    {
    }

    /**
     * @return int
     */
    public function getIdDeclarationService(): int
    {
        return $this->idDeclarationService;
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
     * @return ?string
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }

    /**
     * @return int
     */
    public function getIdIntervention(): int
    {
        return $this->idIntervention;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idDeclarationServiceTag" => $this->idDeclarationService,
            "idIntervenantTag" => $this->idIntervenant,
            "idUniteServiceAnneeTag" => $this->idUniteServiceAnnee,
            "modeTag" => $this->mode,
            "idInterventionTag" => $this->idIntervention
        ];
    }
}