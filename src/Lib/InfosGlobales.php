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

    public static function lireDepartement(): ?string
    {
        return Cookie::lire("departement");
    }

    public static function lireAnnee(): ?int
    {
        return Cookie::lire("annee");
    }

    public static function supprimerInfos()
    {
        Cookie::supprimer("departement");
        Cookie::supprimer("annee");
    }
}