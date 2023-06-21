<?php

namespace App\Sensei\Model\DataObject;

class DeclarationService extends AbstractDataObject
{
    public function __construct(
        private int     $idDeclarationService,
        private int     $idIntervenant,
        private int     $idUniteServiceAnnee,
        private ?string $mode,
        private int     $idIntervention,
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
     * @param int $idDeclarationService
     */
    public function setIdDeclarationService(int $idDeclarationService): void
    {
        $this->idDeclarationService = $idDeclarationService;
    }

    /**
     * @param int $idIntervenant
     */
    public function setIdIntervenant(int $idIntervenant): void
    {
        $this->idIntervenant = $idIntervenant;
    }

    /**
     * @param int $idUniteServiceAnnee
     */
    public function setIdUniteServiceAnnee(int $idUniteServiceAnnee): void
    {
        $this->idUniteServiceAnnee = $idUniteServiceAnnee;
    }

    /**
     * @param string|null $mode
     */
    public function setMode(?string $mode): void
    {
        $this->mode = $mode;
    }

    /**
     * @param int $idIntervention
     */
    public function setIdIntervention(int $idIntervention): void
    {
        $this->idIntervention = $idIntervention;
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