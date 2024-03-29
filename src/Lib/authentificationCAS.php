<?php

// Paramétrage des données de session
use App\Sensei\Configuration\ConfigurationBDDMariaDB;
use App\Sensei\Lib\ConnexionUtilisateur;
use App\Sensei\Lib\InfosGlobales;
use App\Sensei\Model\Repository\ConnexionBaseDeDonnees;
use App\Sensei\Model\Repository\DepartementRepository;
use App\Sensei\Model\Repository\IntervenantRepository;
use App\Sensei\Model\Repository\ServiceAnnuelRepository;
use App\Sensei\Service\DepartementService;
use App\Sensei\Service\IntervenantService;

ini_set("session.gc_maxlifetime", 3600);
ini_set("session.lifetime", 3600);
ini_set("session.cookie_lifetime", 3600);

//@session_start();

/**
 * Example for a simple cas 2.0 client
 *
 * PHP Version 5
 *
 * @file example_simple.php
 * @category Authentication
 * @package PhpCAS
 * @author Joachim Fritschi <jfritschi@freenet.de>
 * @author Adam Franco <afranco@middlebury.edu>
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link https://wiki.jasig.org/display/CASC/phpCAS
 */
// Load the settings from the central config file
//require_once 'configUM.php';
// Load the CAS lib

require_once __DIR__ . "/../../vendor/apereo/phpcas/source/CAS.php";


// Enable debugging
phpCAS::setLogger();
// Initialize phpCAS
// phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);

phpCAS::client(CAS_VERSION_3_0, 'cas.umontpellier.fr', 443, '/cas/', "https://eratosthene.imag.umontpellier.fr/");
// For production use set the CA certificate that is the issuer of the cert
// on the CAS server and uncomment the line below
//phpCAS::setCasServerCACert($cas_server_ca_cert_path);
// For quick testing you can disable SSL validation of the CAS server.
// THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION.
// VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL!

phpCAS::setNoCasServerValidation();
// force CAS authentication
phpCAS::forceAuthentication();

$post = $_POST["logout"] ?? null;

if (isset($post)) {
    phpCAS::logout();
}

$login = phpCAS::getUser();

$e = phpCAS::getAttributes();
$uid = $e['uid'];
$email = $e['mail'];

$intervenantService = new IntervenantService(new IntervenantRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB())));
$serviceAnnuelService = new ServiceAnnuelRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB()));
$departementService = new DepartementService(new DepartementRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB())),
    new ServiceAnnuelRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB())),
    new IntervenantRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB())));

if (isset($uid)) {
    $connexionUtilisateur = new ConnexionUtilisateur(new IntervenantRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB())));
    $intervenant = $intervenantService->recupererParUID($uid);
    if (!$connexionUtilisateur->estConnecte() && isset($intervenant)) {
        $connexionUtilisateur->connecter($intervenant->getIdIntervenant());
        $serviceAnnuel = $serviceAnnuelService->recupererParIntervenantAnnuelPlusRecent($connexionUtilisateur->getIdUtilisateurConnecte());
        InfosGlobales::enregistrerDepartement($departementService->recupererParIdentifiant($serviceAnnuel->getIdDepartement())->getLibDepartement());
        InfosGlobales::enregistrerAnnee($serviceAnnuel->getMillesime());
    }
} else if (isset($email)) {
    $connexionUtilisateur = new ConnexionUtilisateur(new IntervenantRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB())));
    $intervenant = $intervenantService->recupererParEmailInstitutionnel($email);
    if (!$connexionUtilisateur->estConnecte() && isset($intervenant)) {
        $connexionUtilisateur->connecter($intervenant->getIdIntervenant());
        $serviceAnnuel = $serviceAnnuelService->recupererParIntervenantAnnuelPlusRecent($connexionUtilisateur->getIdUtilisateurConnecte());
        InfosGlobales::enregistrerDepartement($departementService->recupererParIdentifiant($serviceAnnuel->getIdDepartement())->getLibDepartement());
        InfosGlobales::enregistrerAnnee($serviceAnnuel->getMillesime());
    }
} else {
    die("Vous n'êtes pas autorisé à accéder à l'application !");
}

// at this step, the user has been authenticated by the CAS server
// and the user's login name can be read with phpCAS::getUser().

