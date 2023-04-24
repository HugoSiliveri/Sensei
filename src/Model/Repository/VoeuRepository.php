<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\Voeu;

/**
 * @name VoeuRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des vœux.
 *
 * @author Hugo Siliveri
 *
 */
class VoeuRepository extends AbstractRepository
{

    /**
     * Retourne le nom de la table contenant les données de Voeu.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "Voeu";
    }

    /**
     * Retourne la clé primaire de la table Voeu.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idVoeu";
    }

    /**
     * Retourne le nom de tous les attributs de la table Voeu.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idVoeu", "idIntervenant", "idUniteServiceAnnee", "idIntervention"];
    }

    /** Construit un objet Voeu à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return Voeu
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new Voeu(
            $objetFormatTableau["idVoeu"],
            $objetFormatTableau["idIntervenant"],
            $objetFormatTableau["idUniteServiceAnnee"],
            $objetFormatTableau["idIntervention"]
        );
    }
}