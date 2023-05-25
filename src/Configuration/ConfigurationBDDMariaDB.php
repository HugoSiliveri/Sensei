<?php

namespace App\Sensei\Configuration;

/**
 * @name ConfigurationBDDMariaDB
 *
 * @tutorial Classe de configuration pour la connexion vers la base de données MariaDB.
 *
 * @author Hugo Siliveri
 *
 */
class ConfigurationBDDMariaDB implements ConfigurationBDDInterface
{

    /**
     * @var string Nom de la base de données.
     */
    private string $nomBDD = "sensei2";

    /**
     * @var string Adresse IP de la base de données.
     */
    private string $hostname = "172.18.0.2";


    /**
     * @inheritDoc
     */
    public function getLogin(): string
    {
        return "root";
    }

    /**
     * @inheritDoc
     */
    public function getMotDePasse(): string
    {
        return "##123456azerty**";
    }

    public function getDSN(): string
    {
        return "mysql:host=$this->hostname;dbname=$this->nomBDD";
    }

    /**
     * Retourne une liste d'options pour la base de données.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return array();
    }


}