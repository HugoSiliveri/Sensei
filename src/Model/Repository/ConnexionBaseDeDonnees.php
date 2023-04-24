<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Configuration\Configuration;
use App\Sensei\Configuration\ConfigurationBDDInterface;
use PDO;

/**
 * @name ConnexionBaseDeDonneesInterface
 *
 * @tutorial Classe qui s'occupe de gérer la connexion de la base de données
 *
 * @author Hugo Siliveri
 *
 */
class ConnexionBaseDeDonnees implements ConnexionBaseDeDonneesInterface
{

    private PDO $pdo;
    private ConfigurationBDDInterface $configurationBDD;

    /**
     * Constructeur de ConnexionBaseDeDonnees.
     * Récupère la configuration de la connexion à la base de données MariaDB, puis crée la connexion
     * @param ConfigurationBDDInterface $configurationBDD
     */
    public function __construct(ConfigurationBDDInterface $configurationBDD)
    {
        $configuration = new Configuration($configurationBDD);
        $this->configurationBDD = $configuration->getConfigurationBDD();

        // Connexion à la base de données
        $this->pdo = new PDO(
            $this->configurationBDD->getLogin(),
            $this->configurationBDD->getMotDePasse(),
        );

        // On active le mode d'affichage des erreurs, et le lancement d'exception en cas d'erreur
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }

    /**
     * @inheritDoc
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

}