<?php

namespace App\Sensei\Model\DataObject;

class Nature extends AbstractDataObject
{
    public function __construct(
        private int     $idNature,
        private ?string $libNature
    )
    {
    }

    /**
     * @return int
     */
    public function getIdNature(): int
    {
        return $this->idNature;
    }

    /**
     * @return string|null
     */
    public function getLibNature(): ?string
    {
        return $this->libNature;
    }

    /**
     * @param int $idNature
     */
    public function setIdNature(int $idNature): void
    {
        $this->idNature = $idNature;
    }

    /**
     * @param string|null $libNature
     */
    public function setLibNature(?string $libNature): void
    {
        $this->libNature = $libNature;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idNatureTag" => $this->idNature,
            "libNatureTag" => $this->libNature
        ];
    }
}