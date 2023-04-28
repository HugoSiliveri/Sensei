<?php

use App\Sensei\Configuration\ConfigurationBDDMariaDB;
use App\Sensei\Model\Repository\ConnexionBaseDeDonnees;
use App\Sensei\Model\Repository\IntervenantRepository;
use App\Sensei\Service\IntervenantService;

require_once __DIR__ . '/../../vendor/autoload.php';

// lancement de la requête SQL avec selectByName et
// récupération du résultat de la requête SQL
// ...
$intervenantArray = (new IntervenantService(new IntervenantRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB()))))->recupererRequeteIntervenant();
// affichage en format JSON du résultat précédent
// ...
echo json_encode($intervenantArray);
