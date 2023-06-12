<?php

namespace App\Sensei\Lib;

use App\Sensei\Model\HTTP\Cookie;

class InfosGlobales
{
    public static function enregistrerDepartement(string $departement)
    {
        Cookie::enregistrer("departement", $departement);
    }

    public static function enregistrerAnnee(int $annee)
    {
        Cookie::enregistrer("annee", $annee);
    }

    public static function enregistrerIdIntervenantUsurpe(int $idIntervenant){
        Cookie::enregistrer("idIntervenantUsurpe", $idIntervenant);
    }

    public static function lireDepartement(): ?string
    {
        return Cookie::lire("departement");
    }

    public static function lireAnnee(): ?int
    {
        return Cookie::lire("annee");
    }

    public static function lireIdIntervenantUsurpe(): ?int
    {
        return Cookie::lire("idIntervenantUsurpe");
    }

    public static function supprimerInfos()
    {
        Cookie::supprimer("departement");
        Cookie::supprimer("annee");
        Cookie::supprimer("idIntervenantUsurpe");
    }

    public static function arreterUsurpation(){
        Cookie::enregistrer("idIntervenantUsurpe", null);
        Cookie::supprimer("idIntervenantUsurpe");
    }
}