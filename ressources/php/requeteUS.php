<?php

use App\Sensei\Configuration\ConfigurationBDDMariaDB;
use App\Sensei\Model\Repository\ConnexionBaseDeDonnees;
use App\Sensei\Model\Repository\UniteServiceRepository;
use App\Sensei\Service\UniteServiceService;

require_once __DIR__ . '/../../vendor/autoload.php';

// lancement de la requête SQL avec selectByName et
// récupération du résultat de la requête SQL
// ...
$uniteServiceArray = (new UniteServiceService(new UniteServiceRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB()))))->recupererRequeteUniteService();
// affichage en format JSON du résultat précédent
// ...
echo json_encode($uniteServiceArray);
