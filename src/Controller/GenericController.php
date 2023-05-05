<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @name GenericController
 *
 * @tutorial GenericController est une classe qui regroupe les méthodes communes aux différents controllers.
 * Toutes les méthodes de cette classe sont statiques et accessibles uniquement par les autres controllers.
 *
 * @author Hugo Siliveri
 *
 */
class GenericController
{
    /**
     * Méthode qui affiche un message d'erreur pour un controller et qui affiche la vue erreur.php
     *
     * @param $errorMessage
     * @param $statusCode
     * @return Response
     */
    public static function afficherErreur($errorMessage = "", $statusCode = 400): Response
    {
        return self::afficherTwig("erreur.twig", ["errorMessage" => $errorMessage]);
    }

    /**
     *  Méthode qui permet d'afficher une vue Twig
     * @param string $cheminVue chemin de la vue
     * @param array $parametres liste des éléments qui seront utilisé dans les vues
     * @throw
     */
    protected static function afficherTwig(string $cheminVue, array $parametres = []): Response
    {
        try {
            $conteneur = URLRouter::getConteneur();
            /** @var Environment $twig */
            $twig = $conteneur->get("twig");
            return new Response($twig->render($cheminVue, $parametres));
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            return new Response("Une erreur s'est produite lors du rendu de la vue : " . $e->getMessage(), 500);
        }
    }

    /**
     * Méthode qui permet de rediriger l'utilisateur vers une différente page
     *
     * @param string $route
     * @param array $option
     * @return RedirectResponse
     */
    protected static function rediriger(string $route = "", array $option = []): Response
    {
        $conteneur = URLRouter::getConteneur();
        $generateurUrl = $conteneur->get("generateurUrl");
        $url = $generateurUrl->generate($route, $option);
        $urlFinal = "Location: " . $url;
        header($urlFinal);
        return new RedirectResponse($url);
    }
}