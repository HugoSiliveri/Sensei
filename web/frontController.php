<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . "/../src/Lib/authentificationCAS.php";

use App\Sensei\Controller\URLRouter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Elle récupère les informations de la requête depuis les variables globales $_GET, $_POST, …
 * Elle est à peu près équivalente à :
 * $requete = new Request($_GET,$_POST,[],$_COOKIE,$_FILES,$_SERVER);
 */
$requete = Request::createFromGlobals();


URLRouter::traiterRequete($requete)->send();
