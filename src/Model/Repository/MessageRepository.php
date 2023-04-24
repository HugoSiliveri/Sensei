<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Message;

/**
 * @name MessageRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des messages.
 *
 * @author Hugo Siliveri
 *
 */
class MessageRepository extends AbstractRepository
{

    /**
     * Retourne le nom de la table contenant les données de Message.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Message";
    }

    /**
     * Retourne la clé primaire de la table Message.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idMessage";
    }

    /**
     * Retourne le nom de tous les attributs de la table Message.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idMessage", "sujet", "texte", "date", "auteur", "destinataires", "priorite", "traitement", "typeObjet", "idObjet"];
    }

    /** Construit un objet Message à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Message
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Message(
            $objetFormatTableau["idMessage"],
            $objetFormatTableau["sujet"],
            $objetFormatTableau["texte"],
            $objetFormatTableau["date"],
            $objetFormatTableau["auteur"],
            $objetFormatTableau["destinataires"],
            $objetFormatTableau["priorite"],
            $objetFormatTableau["traitement"],
            $objetFormatTableau["typeObjet"],
            $objetFormatTableau["idObjet"]
        );
    }
}