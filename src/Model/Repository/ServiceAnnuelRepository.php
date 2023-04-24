<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\ServiceAnnuel;

/**
 * @name ServiceAnnuelRepository
 *
 * @tutorial Classe contenant toutes les méthodes gérant la persistance des données des services annuels.
 *
 * @author Hugo Siliveri
 *
 */
class ServiceAnnuelRepository extends AbstractRepository
{

    /**
     * Retourne le nom de la table contenant les données de ServiceAnnuel.
     * @return string
     */
    protected function getNomTable(): string
    {
        return "ServiceAnnuel";
    }

    /**
     * Retourne la clé primaire de la table ServiceAnnuel.
     * @return string
     */
    protected function getNomClePrimaire(): string
    {
        return "idServiceAnnuel";
    }

    /**
     * Retourne le nom de tous les attributs de la table ServiceAnnuel.
     * @return string[] le tableau contenant tous les noms des attributs
     */
    protected function getNomsColonnes(): array
    {
        return ["idServiceAnnuel", "idDepartement", "idIntervenant", "millesime", "idEmploi", "serviceStatuaire", "serviceFait", "delta", "deleted"];
    }

    /** Construit un objet ServiceAnnuel à partir d'un tableau donné en paramètre.
     * @param array $objetFormatTableau
     * @return ServiceAnnuel
     */
    protected function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject
    {
        return new ServiceAnnuel(
            $objetFormatTableau["idServiceAnnuel"],
            $objetFormatTableau["idDepartement"],
            $objetFormatTableau["idIntervenant"],
            $objetFormatTableau["millesime"],
            $objetFormatTableau["idEmploi"],
            $objetFormatTableau["serviceStatuaire"],
            $objetFormatTableau["serviceFait"],
            $objetFormatTableau["delta"],
            $objetFormatTableau["deleted"]
        );
    }
}