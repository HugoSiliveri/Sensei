<?php

namespace App\Sensei\Model\DataObject;

class Payeur extends AbstractDataObject
{
    public function __construct(
        private int $idPayeur,
        private string $libPayeur
    )
    {
    }

    /**
     * @return int
     */
    public function getIdPayeur(): int
    {
        return $this->idPayeur;
    }

    /**
     * @return string
     */
    public function getLibPayeur(): string
    {
        return $this->libPayeur;
    }


    /**
     * @inheritDoc
     */
    public function recupererFormatTableau(): array
    {
        return [
            "idPayeur" => $this->idPayeur,
            "libPayeur" => $this->libPayeur,
        ];
    }
}