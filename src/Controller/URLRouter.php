<?php

namespace App\Sensei\Controller;

use App\Sensei\Configuration\ConfigurationBDDMariaDB;
use App\Sensei\Lib\ConnexionUtilisateur;
use App\Sensei\Lib\MessageFlash;
use App\Sensei\Model\Repository\AppartenirRepository;
use App\Sensei\Model\Repository\ColorationRepository;
use App\Sensei\Model\Repository\ConnexionBaseDeDonnees;
use App\Sensei\Model\Repository\DepartementRepository;
use App\Sensei\Model\Repository\DroitRepository;
use App\Sensei\Model\Repository\EmploiRepository;
use App\Sensei\Model\Repository\IntervenantRepository;
use App\Sensei\Model\Repository\InterventionRepository;
use App\Sensei\Model\Repository\NatureRepository;
use App\Sensei\Model\Repository\PayeurRepository;
use App\Sensei\Model\Repository\ResponsableUSRepository;
use App\Sensei\Model\Repository\ServiceAnnuelRepository;
use App\Sensei\Model\Repository\StatutRepository;
use App\Sensei\Model\Repository\UniteServiceAnneeRepository;
use App\Sensei\Model\Repository\UniteServiceRepository;
use App\Sensei\Model\Repository\VoeuRepository;
use App\Sensei\Service\AppartenirService;
use App\Sensei\Service\ColorationService;
use App\Sensei\Service\DepartementService;
use App\Sensei\Service\DroitService;
use App\Sensei\Service\EmploiService;
use App\Sensei\Service\IntervenantService;
use App\Sensei\Service\InterventionService;
use App\Sensei\Service\NatureService;
use App\Sensei\Service\PayeurService;
use App\Sensei\Service\ResponsableUSService;
use App\Sensei\Service\ServiceAnnuelService;
use App\Sensei\Service\StatutService;
use App\Sensei\Service\UniteServiceAnneeService;
use App\Sensei\Service\UniteServiceService;
use App\Sensei\Service\VoeuService;
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

        $uniteServiceAnneeRepository = $conteneur->register('unite_service_annee_repository', UniteServiceAnneeRepository::class);
        $uniteServiceAnneeRepository->setArguments([new Reference('connexion_base')]);

        $statutRepository = $conteneur->register('statut_repository', StatutRepository::class);
        $statutRepository->setArguments([new Reference('connexion_base')]);

        $droitRepository = $conteneur->register('droit_repository', DroitRepository::class);
        $droitRepository->setArguments([new Reference('connexion_base')]);

        $serviceAnnuelRepository = $conteneur->register('service_annuel_repository', ServiceAnnuelRepository::class);
        $serviceAnnuelRepository->setArguments([new Reference('connexion_base')]);

        $emploiRepository = $conteneur->register('emploi_repository', EmploiRepository::class);
        $emploiRepository->setArguments([new Reference('connexion_base')]);

        $departementRepository = $conteneur->register('departement_repository', DepartementRepository::class);
        $departementRepository->setArguments([new Reference('connexion_base')]);

        $interventionRepository = $conteneur->register('intervention_repository', InterventionRepository::class);
        $interventionRepository->setArguments([new Reference('connexion_base')]);

        $voeuRepository = $conteneur->register('voeu_repository', VoeuRepository::class);
        $voeuRepository->setArguments([new Reference('connexion_base')]);

        $responsableUSRepository = $conteneur->register('responsable_us_repository', ResponsableUSRepository::class);
        $responsableUSRepository->setArguments([new Reference('connexion_base')]);

        $payeurRepository = $conteneur->register('payeur_repository', PayeurRepository::class);
        $payeurRepository->setArguments([new Reference('connexion_base')]);

        $appartenirRepository = $conteneur->register('appartenir_repository', AppartenirRepository::class);
        $appartenirRepository->setArguments([new Reference('connexion_base')]);

        $natureRepository = $conteneur->register('nature_repository', NatureRepository::class);
        $natureRepository->setArguments([new Reference('connexion_base')]);

        $colorationRepository = $conteneur->register('coloration_repository', ColorationRepository::class);
        $colorationRepository->setArguments([new Reference('connexion_base')]);

        $connexionUtilisateur = $conteneur->register('connexion_utilisateur', ConnexionUtilisateur::class);
        $connexionUtilisateur->setArguments([new Reference('intervenant_repository')]);

        $intervenantController = $conteneur->register('intervenant_controller', IntervenantController::class);
        $intervenantController->setArguments([new Reference('intervenant_service'), new Reference('statut_service'),
            new Reference('droit_service'), new Reference('service_annuel_service'), new Reference('emploi_service'),
            new Reference('departement_service'), new Reference('unite_service_service'), new Reference('unite_service_annee_service'), new Reference('intervention_service'),
            new Reference('voeu_service'), new Reference('responsable_us_service'), new Reference('connexion_utilisateur')]);

        $uniteServiceController = $conteneur->register('unite_service_controller', UniteServiceController::class);
        $uniteServiceController->setArguments([new Reference('unite_service_service'), new Reference('unite_service_annee_service'),
            new Reference('voeu_service'), new Reference('intervenant_service'), new Reference('intervention_service'),
            new Reference('payeur_service'), new Reference('departement_service'), new Reference('appartenir_service'),
            new Reference('nature_service'), new Reference('coloration_service')]);

        $voeuController = $conteneur->register('voeu_controller', VoeuController::class);
        $voeuController->setArguments([new Reference('voeu_service')]);

        $intervenantService = $conteneur->register('intervenant_service', IntervenantService::class);
        $intervenantService->setArguments([new Reference("intervenant_repository"), new Reference("connexion_utilisateur")]);

        $uniteServiceService = $conteneur->register('unite_service_service', UniteServiceService::class);
        $uniteServiceService->setArguments([new Reference('unite_service_repository')]);

        $uniteServiceAnneeService = $conteneur->register('unite_service_annee_service', UniteServiceAnneeService::class);
        $uniteServiceAnneeService->setArguments([new Reference('unite_service_annee_repository')]);

        $statutService = $conteneur->register('statut_service', StatutService::class);
        $statutService->setArguments([new Reference('statut_repository')]);

        $droitService = $conteneur->register('droit_service', DroitService::class);
        $droitService->setArguments([new Reference('droit_repository')]);

        $serviceAnnuelService = $conteneur->register('service_annuel_service', ServiceAnnuelService::class);
        $serviceAnnuelService->setArguments([new Reference('service_annuel_repository')]);

        $emploiService = $conteneur->register('emploi_service', EmploiService::class);
        $emploiService->setArguments([new Reference('emploi_repository')]);

        $departementService = $conteneur->register('departement_service', DepartementService::class);
        $departementService->setArguments([new Reference('departement_repository')]);

        $interventionService = $conteneur->register('intervention_service', InterventionService::class);
        $interventionService->setArguments([new Reference('intervention_repository')]);

        $voeuService = $conteneur->register('voeu_service', VoeuService::class);
        $voeuService->setArguments([new Reference('voeu_repository')]);

        $responsableUSService = $conteneur->register('responsable_us_service', ResponsableUSService::class);
        $responsableUSService->setArguments([new Reference('responsable_us_repository')]);

        $payeurService = $conteneur->register('payeur_service', PayeurService::class);
        $payeurService->setArguments([new Reference('payeur_repository')]);

        $appartenirService = $conteneur->register('appartenir_service', AppartenirService::class);
        $appartenirService->setArguments([new Reference('appartenir_repository')]);

        $natureService = $conteneur->register('nature_service', NatureService::class);
        $natureService->setArguments([new Reference('nature_repository')]);

        $colorationService = $conteneur->register('coloration_service', ColorationService::class);
        $colorationService->setArguments([new Reference('coloration_repository')]);

        /* Instantiation d'une collection de routes */
        $routes = new RouteCollection();

        /* Création et ajout des routes à la collection */
        $routeParDefaut = new Route("/", [
            "_controller" => ["intervenant_controller", "afficherAccueil"]
        ]);

        $routeAfficherListeIntervenants = new Route("/intervenants", [
            "_controller" => ["intervenant_controller", "afficherListe"]
        ]);
        $routeAfficherListeIntervenants->setMethods(["GET"]);

        $routeChercherIntervenant = new Route("/intervenants", [
            "_controller" => ["intervenant_controller", "chercherIntervenant"]
        ]);
        $routeChercherIntervenant->setMethods(["POST"]);

        $routeAfficherFormulaireCreationIntervenant = new Route("/creerIntervenant", [
            "_controller" => ["intervenant_controller", "afficherFormulaireCreation"]
        ]);
        $routeAfficherFormulaireCreationIntervenant->setMethods(["GET"]);

        $routeCreerIntervenantDepuisFormulaire = new Route("/creerIntervenant", [
            "_controller" => ["intervenant_controller", "creerDepuisFormulaire"]
        ]);
        $routeCreerIntervenantDepuisFormulaire->setMethods(["POST"]);

        $routeAfficherFormulaireCreationUniteService = new Route("/creerUniteService", [
            "_controller" => ["unite_service_controller", "afficherFormulaireCreation"]
        ]);
        $routeAfficherFormulaireCreationUniteService->setMethods(["GET"]);

        $routeCreerUniteServiceDepuisFormulaire = new Route("/creerUniteService", [
            "_controller" => ["unite_service_controller", "creerDepuisFormulaire"]
        ]);
        $routeCreerUniteServiceDepuisFormulaire->setMethods(["POST"]);

        $routeAfficherDetailIntervenant = new Route("/intervenants/{idIntervenant}", [
            "_controller" => ["intervenant_controller", "afficherDetail"]
        ]);
        $routeAfficherDetailIntervenant->setMethods(["GET"]);

        $routeAfficherListeUniteServices = new Route("/unitesServices", [
            "_controller" => ["unite_service_controller", "afficherListe"]
        ]);
        $routeAfficherListeUniteServices->setMethods(["GET"]);

        $routeChercherUniteService = new Route("/unitesServices", [
            "_controller" => ["unite_service_controller", "chercherUniteService"]
        ]);
        $routeChercherUniteService->setMethods(["POST"]);

        $routeAfficherDetailUniteService = new Route("/unitesServices/{idUniteService}", [
            "_controller" => ["unite_service_controller", "afficherDetail"]
        ]);
        $routeAfficherDetailUniteService->setMethods(["GET"]);

        $routeExporterEnCSVIntervenant = new Route("/export", [
            "_controller" => ["voeu_controller", "exporterEnCSV"]
        ]);
        $routeExporterEnCSVIntervenant->setMethods(["GET"]);

        /* Ajoute les routes dans la collection et leur associe un nom */
        $routes->add("accueil", $routeParDefaut);
        $routes->add("afficherListeIntervenants", $routeAfficherListeIntervenants);
        $routes->add("afficherListeUnitesServices", $routeAfficherListeUniteServices);
        $routes->add("afficherDetailIntervenant", $routeAfficherDetailIntervenant);
        $routes->add("exporterEnCSV", $routeExporterEnCSVIntervenant);
        $routes->add("afficherDetailUniteService", $routeAfficherDetailUniteService);
        $routes->add("chercherIntervenant", $routeChercherIntervenant);
        $routes->add("chercherUniteService", $routeChercherUniteService);
        $routes->add("afficherFormulaireCreationIntervenant", $routeAfficherFormulaireCreationIntervenant);
        $routes->add("creerIntervenantDepuisFormulaire", $routeCreerIntervenantDepuisFormulaire);
        $routes->add("afficherFormulaireCreationUniteService", $routeAfficherFormulaireCreationUniteService);
        $routes->add("creerUniteServiceDepuisFormulaire", $routeCreerUniteServiceDepuisFormulaire);

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
        $twig->addGlobal('messagesFlash', new MessageFlash());

        $conteneur->set("assistantUrl", $assistantUrl);
        $conteneur->set("generateurUrl", $generateurUrl);
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
     * @return mixed
     */
    public static function getConteneur()
    {
        return self::$conteneur;
    }

    /**
     * @param mixed $conteneur
     */
    public static function setConteneur($conteneur): void
    {
        self::$conteneur = $conteneur;
    }
}