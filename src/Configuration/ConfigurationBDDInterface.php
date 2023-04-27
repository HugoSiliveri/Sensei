<?php

namespace App\Sensei\Configuration;

/**
 * @name ConfigurationBDDInterface
 *
 * @tutorial Interface pour les multiples connexions vers les possibles bases de données de l'application.
 *
 * @author Hugo Siliveri
 *
 */
interface ConfigurationBDDInterface
{

    /**
     * Retourne le login de la BDD.
     *
     * @return string
     */
    public function getLogin(): string;

    /**
     * Retourne le mot de passe de la BDD.
     *
     * @return string
     */
    public function getMotDePasse(): string;

}