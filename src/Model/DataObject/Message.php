<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Message
 *
 * @tutorial Classe qui représente l'objet Message dans la base de données.
 * L'objet Message correspond à un mail envoyé depuis l'application
 *
 * @author Hugo Siliveri
 *
 */
class Message extends AbstractDataObject
{

    public function __construct(
        private int    $idMessage,
        private string $sujet,
        private string $texte,
        private string $date,
        private int    $auteur,
        private string $destinataires,
        private string $priorite,
        private string $traitement,
        private string $typeObjet,
        private int    $idObjet
    )
    {
    }

    /**
     * @return int
     */
    public function getIdMessage(): int
    {
        return $this->idMessage;
    }

    /**
     * @return string
     */
    public function getSujet(): string
    {
        return $this->sujet;
    }

    /**
     * @return string
     */
    public function getTexte(): string
    {
        return $this->texte;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getAuteur(): int
    {
        return $this->auteur;
    }

    /**
     * @return string
     */
    public function getDestinataires(): string
    {
        return $this->destinataires;
    }

    /**
     * @return string
     */
    public function getPriorite(): string
    {
        return $this->priorite;
    }

    /**
     * @return string
     */
    public function getTraitement(): string
    {
        return $this->traitement;
    }

    /**
     * @return string
     */
    public function getTypeObjet(): string
    {
        return $this->typeObjet;
    }

    /**
     * @return int
     */
    public function getIdObjet(): int
    {
        return $this->idObjet;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idMessageTag" => $this->idMessage,
            "sujetTag" => $this->sujet,
            "texteTag" => $this->texte,
            "dateTag" => $this->date,
            "auteurTag" => $this->auteur,
            "destinatairesTag" => $this->destinataires,
            "prioriteTag" => $this->priorite,
            "traitementTag" => $this->traitement,
            "typeObjetTag" => $this->typeObjet,
            "idObjetTag" => $this->idObjet
        ];
    }
}