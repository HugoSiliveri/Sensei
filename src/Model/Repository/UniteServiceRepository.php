<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\UniteService;

/**
 * @name UniteServiceRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des unités services.
 *
 * @author Hugo Siliveri
 *
 */
class UniteServiceRepository extends AbstractRepository
{

    /**
     * Retourne le nom de la table contenant les données de UniteService.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "UniteService";
    }

    /**
     * Retourne la clé primaire de la table UniteService.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idUniteService";
    }

    /**
     * Retourne le nom de tous les attributs de la table UniteService.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idUniteService", "idUSReferentiel", "libUS", "nature", "ancetre", "anneeOuverture", "anneeCloture", "ECTS",
        "heuresCM", "heuresTD", "heuresTP", "heuresStage", "heuresTerrain", "semestre", "saison", "payeur", "validite", "deleted"];
    }

    /** Construit un objet UniteService à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return UniteService
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new UniteService(
            $objetFormatTableau["idUniteService"],
            $objetFormatTableau["idUSReferentiel"],
            $objetFormatTableau["libUS"],
            $objetFormatTableau["nature"],
            $objetFormatTableau["ancetre"],
            $objetFormatTableau["anneeOuverture"],
            $objetFormatTableau["anneeCloture"],
            $objetFormatTableau["ECTS"],
            $objetFormatTableau["heuresCM"],
            $objetFormatTableau["heuresTD"],
            $objetFormatTableau["heuresTP"],
            $objetFormatTableau["heuresStage"],
            $objetFormatTableau["heuresTerrain"],
            $objetFormatTableau["semestre"],
            $objetFormatTableau["saison"],
            $objetFormatTableau["payeur"],
            $objetFormatTableau["validite"],
            $objetFormatTableau["deleted"]
        );
    }
}