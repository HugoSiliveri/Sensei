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
     * @inheritDoc
     */
    public function recupererFormatTableau(): array
    {
        return [
            "idNature" => $this->idNature,
            "libNature" => $this->libNature
        ];
    }
}