<?php

namespace App\Sensei\Controller;

use App\Sensei\Configuration\ConfigurationBDDMariaDB;
use App\Sensei\Lib\ConnexionUtilisateur;
use App\Sensei\Model\Repository\ConnexionBaseDeDonnees;
use App\Sensei\Model\Repository\IntervenantRepository;
use App\Sensei\Model\Repository\UniteServiceRepository;
use App\Sensei\Service\IntervenantService;
use App\Sensei\Service\UniteServiceService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

/**
 * @name URLRouter
 *
 * @tutorial URLRouter est une classe va permettre d'appeler les bonnes méthodes des controllers en fonction de l'URL.
 *
 * @author Hugo Siliveri
 *
 */
class URLRouter
{

    /* Initialisation du conteneur de service */
    private static mixed $conteneur;

    /**
     * Méthode qui va renvoyer une réponse HTTP suivant l'action et les paramètres passés dans la requête HTTP
     *
     * @param Request $requete requête HTTP sous forme de classe PHP
     * @return Response réponse HTTP sous forme de classe PHP
     */
    public static function traiterRequete(Request $requete): Response
    {
        /* Création d'un conteneur pour stocker les services */
        $conteneur = new ContainerBuilder();

        $conteneur->register('config_bdd', ConfigurationBDDMariaDB::class);

        /* On stocke la classe qui sera appelée dans le conteneur ainsi que les arguments nécessaires pour le constructeur*/
        $connexionBaseService = $conteneur->register('connexion_base', ConnexionBaseDeDonnees::class);
        $connexionBaseService->setArguments([new Reference('config_bdd')]);

        $intervenantRepository = $conteneur->register('intervenant_repository', IntervenantRepository::class);
        $intervenantRepository->setArguments([new Reference('connexion_base')]);

        $uniteServiceRepository = $conteneur->register('unite_service_repository', UniteServiceRepository::class);
        $uniteServiceRepository->setArguments([new Reference('connexion_base')]);

        $connexionUtilisateur = $conteneur->register('connexion_utilisateur', ConnexionUtilisateur::class);
        $connexionUtilisateur->setArguments([new Reference('intervenant_repository')]);

        $intervenantService = $conteneur->register('intervenant_service', IntervenantService::class);
        $intervenantService->setArguments([new Reference("intervenant_repository"), new Reference("connexion_utilisateur")]);

        $intervenantController = $conteneur->register('intervenant_controller', IntervenantController::class);
        $intervenantController->setArguments([new Reference('intervenant_service'), new Reference('connexion_utilisateur')]);

        $uniteServiceService = $conteneur->register('unite_service_service', UniteServiceService::class);
        $uniteServiceService->setArguments([new Reference("unite_service_repository")]);

        $uniteServiceController = $conteneur->register('unite_service_controller', UniteServiceController::class);
        $uniteServiceController->setArguments([new Reference('unite_service_service')]);

        $genericController = $conteneur->register("generic_controller", GenericController::class);

        /* Instantiation d'une collection de routes */
        $routes = new RouteCollection();

        /* Création et ajout des routes à la collection */
        $routeParDefaut = new Route("/", [
            "_controller" => ["intervenant_controller", "afficherListe"]//"generic_controller", "afficherAccueil"]
        ]);

        $routeAfficherListeIntervenants = new Route("/intervenants", [
            "_controller" => ["intervenant_controller", "afficherListe"]
        ]);
        $routeAfficherListeIntervenants->setMethods(["GET"]);

        $routeAfficherListeUniteServices = new Route("/unitesServices", [
           "_controller" => ["unite_service_controller", "afficherListe"]
        ]);
        $routeAfficherListeUniteServices->setMethods(["GET"]);

        /* Ajoute les routes dans la collection et leur associe un nom */
        $routes->add("accueil", $routeParDefaut);
        $routes->add("afficherListeIntervenants", $routeAfficherListeIntervenants);
        $routes->add("afficherListeUnitesServices", $routeAfficherListeUniteServices);

        $contexteRequete = (new RequestContext())->fromRequest($requete);

        /* URLMatcher va permettre de faire l’association entre une URL et une action */
        $associateurUrl = new UrlMatcher($routes, $contexteRequete);
        /* URLHelper va générer des URL absolues, elle sera utilisée pour les ressources */
        $assistantUrl = new UrlHelper(new RequestStack(), $contexteRequete);

        $generateurUrl = new UrlGenerator($routes, $contexteRequete);

        $twigLoader = new FilesystemLoader(__DIR__ . '/../View/');
        $twig = new Environment(
            $twigLoader,
            [
                'autoescape' => 'html',
                'strict_variables' => true
            ]
        );

        /* Ajout de méthodes callables pour l'appel à une route et aux assets*/
        $callable = [$generateurUrl, "generate"];
        $callable2 = [$assistantUrl, "getAbsoluteUrl"];


        /* On ajoute les fonctions correspondantes à Twig */
        $twig->addFunction(new TwigFunction("route", $callable));
        $twig->addFunction(new TwigFunction("asset", $callable2));

        /* Ajout de variables globales */
        //$twig->addGlobal('messagesFlash', new MessageFlash());

        $conteneur->set("assistantURl", $assistantUrl);
        $conteneur->set("generateurURL", $generateurUrl);
        $conteneur->set("twig", $twig);

        self::setConteneur($conteneur);

        try {
            /**
             * @throws NoConfigurationException  If no routing configuration could be found
             * @throws ResourceNotFoundException If the resource could not be found
             * @throws MethodNotAllowedException If the resource was found but the request method is not allowed
             */
            $donneesRoute = $associateurUrl->match($requete->getPathInfo());
            $requete->attributes->add($donneesRoute);

            $resolveurDeControleur = new ContainerControllerResolver($conteneur);

            /**
             * @throws \LogicException If a controller was found based on the request but it is not callable
             */
            $controleur = $resolveurDeControleur->getController($requete);

            /* Instanciation d'un résolveur d'argument, sert à récupérer l'argument placé en paramètre d'une action de controleur */
            $resolveurDArguments = new ArgumentResolver();

            /**
             * @throws \RuntimeException When no value could be provided for a required argument
             */
            $arguments = $resolveurDArguments->getArguments($requete, $controleur);

            /* Appelle le callback avec ses arguments */
            $reponse = call_user_func_array($controleur, $arguments);
        } catch (ResourceNotFoundException $exception) {
            $reponse = GenericController::afficherErreur($exception->getMessage(), 404);
        } catch (MethodNotAllowedException $exception) {
            $reponse = GenericController::afficherErreur($exception->getMessage(), 405);
        } catch (BadRequestException $exception) {
            $reponse = GenericController::afficherErreur($exception->getMessage(), 406);
        }
        return $reponse;
    }

    /**
     * @param mixed $conteneur
     */
    public static function setConteneur($conteneur): void
    {
        self::$conteneur = $conteneur;
    }

    /**
     * @return mixed
     */
    public static function getConteneur()
    {
        return self::$conteneur;
    }
}