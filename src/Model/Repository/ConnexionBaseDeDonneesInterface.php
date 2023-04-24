<?php

namespace App\Sensei\Model\Repository;

use PDO;

/**
 * @name ConnexionBaseDeDonneesInterface
 *
 * @tutorial Interface qui s'occupe de récupérer le PDO pour la connexion de la base de données
 *
 * @author Hugo Siliveri
 *
 */
interface ConnexionBaseDeDonneesInterface
{
    /** Retourne l'attribut pdo
     * @return PDO
     */
    public function getPdo(): PDO;

}