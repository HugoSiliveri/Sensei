<?php

namespace App\Sensei\Configuration;

/**
 * @name Configuration
 *
 * @tutorial Classe de configuration de l'application.
 *
 * @author Hugo Siliveri
 *
 */
class Configuration
{
    /**
     * @var bool <code>$debug</code> est un booléen qui permet de savoir si le mode debug est activé.
     */
    private static $debug = true;

    /**
     * @var ConfigurationBDDInterface Un objet qui implémente l'interface pour se connecter à la base de données.
     */
    private $configurationBDD;

    /**
     * @var string Attribut qui permet à la personne d'utiliser le site sans problème de chemin pour l'accès aux différents
     * fichiers.
     */
    static private $quiSuisJe = "eratosthene";

    /**
     * @var array|string[] Dictionnaire des personnes avec le début de leur chemin vers le dossier de frontController.php
     * Ce tableau permet d'éviter les problèmes suivant les différentes méthodes d'accès au site (sur une machine personnelle,
     * pas le même nom de dossier, sur un serveur, ...)
     */
    static private $urls = array(
        "localhost" => "http://localhost/Sensei/web/",
        "eratosthene" => "http://eratosthene.imag.umontpellier.fr/Sensei/web"
    );

    /**
     * Constructeur de la classe Configuration et qui initialise la variable <code>$configurationBDD</code>
     *
     * @param ConfigurationBDDInterface $configurationBDD
     */
    public function __construct(ConfigurationBDDInterface $configurationBDD)
    {
        $this->configurationBDD = $configurationBDD;
    }

    /**
     * Méthode qui retourne la configuration <code>$configurationBDD</code>.
     *
     * @return ConfigurationBDDInterface
     */
    public function getConfigurationBDD(): ConfigurationBDDInterface
    {
        return $this->configurationBDD;
    }

    /**
     * Méthode qui retourne l'attibut <code>$debug</code>
     *
     * @return bool <code>true</code> si le debug est activé, <code>false</code> sinon.
     */
    static public function getDebug(): bool
    {
        return Configuration::$debug;
    }

    /**
     * Méthode qui retourne la durée avant l'expiration des sessions en seconde.
     *
     * @return string La valeur en seconde de la durée.
     */
    public static function getDureeExpirationSession(): string
    {
        return 3000;
    }

    /**
     * Méthode qui retourne l'URL de la personne concerné avec l'attribut <code>$quiSuisJe</code> dans le dictionnaire
     * <code>$noms</code>
     *
     * @return string
     */
    public static function getAbsoluteURL(): string
    {
        return static::$urls[static::$quiSuisJe];
    }

}