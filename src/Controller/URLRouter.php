<?php

namespace App\Sensei\Controller;

use App\Sensei\Configuration\ConfigurationBDDMariaDB;
use App\Sensei\Lib\ConnexionUtilisateur;
use App\Sensei\Lib\InfosGlobales;
use App\Sensei\Lib\MessageFlash;
use App\Sensei\Model\Repository\AppartenirRepository;
use App\Sensei\Model\Repository\ColorationRepository;
use App\Sensei\Model\Repository\ComposanteRepository;
use App\Sensei\Model\Repository\ConnexionBaseDeDonnees;
use App\Sensei\Model\Repository\DeclarationServiceRepository;
use App\Sensei\Model\Repository\DepartementRepository;
use App\Sensei\Model\Repository\DroitRepository;
use App\Sensei\Model\Repository\EmploiRepository;
use App\Sensei\Model\Repository\EtatRepository;
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
use App\Sensei\Service\ComposanteService;
use App\Sensei\Service\DeclarationServiceService;
use App\Sensei\Service\DepartementService;
use App\Sensei\Service\DroitService;
use App\Sensei\Service\EmploiService;
use App\Sensei\Service\EtatService;
use App\Sensei\Service\Exception\ServiceException;
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
use LogicException;
use RuntimeException;
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
     * @throws ServiceException
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

        $serviceAnnuelServiceClasse = $conteneur->register('service_annuel_repository', ServiceAnnuelRepository::class);
        $serviceAnnuelServiceClasse->setArguments([new Reference('connexion_base')]);

        $emploiRepository = $conteneur->register('emploi_repository', EmploiRepository::class);
        $emploiRepository->setArguments([new Reference('connexion_base')]);

        $departementServiceClasse = $conteneur->register('departement_repository', DepartementRepository::class);
        $departementServiceClasse->setArguments([new Reference('connexion_base')]);

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

        $composanteRepository = $conteneur->register('composante_repository', ComposanteRepository::class);
        $composanteRepository->setArguments([new Reference('connexion_base')]);

        $etatRepository = $conteneur->register('etat_repository', EtatRepository::class);
        $etatRepository->setArguments([new Reference('connexion_base')]);

        $declarationServiceRepository = $conteneur->register('declaration_service_repository', DeclarationServiceRepository::class);
        $declarationServiceRepository->setArguments([new Reference('connexion_base')]);

        $connexionUtilisateur = $conteneur->register('connexion_utilisateur', ConnexionUtilisateur::class);
        $connexionUtilisateur->setArguments([new Reference('intervenant_repository')]);

        $intervenantController = $conteneur->register('intervenant_controller', IntervenantController::class);
        $intervenantController->setArguments([new Reference('intervenant_service'), new Reference('statut_service'),
            new Reference('droit_service'), new Reference('service_annuel_service'), new Reference('emploi_service'),
            new Reference('departement_service'), new Reference('unite_service_service'), new Reference('unite_service_annee_service'), new Reference('intervention_service'),
            new Reference('voeu_service'), new Reference('responsable_us_service'), new Reference('declaration_service_service'),
            new Reference('connexion_utilisateur')]);

        $uniteServiceController = $conteneur->register('unite_service_controller', UniteServiceController::class);
        $uniteServiceController->setArguments([new Reference('unite_service_service'), new Reference('unite_service_annee_service'),
            new Reference('voeu_service'), new Reference('intervenant_service'), new Reference('intervention_service'),
            new Reference('payeur_service'), new Reference('departement_service'), new Reference('appartenir_service'),
            new Reference('nature_service'), new Reference('coloration_service'), new Reference('declaration_service_service'),
            new Reference('droit_service')]);

        $declarationServiceController = $conteneur->register('declaration_service_controller', DeclarationServiceController::class);
        $declarationServiceController->setArguments([new Reference('declaration_service_service'), new Reference('intervenant_service'),
            new Reference('droit_service'), new Reference('intervention_service')]);

        $natureController = $conteneur->register('nature_controller', NatureController::class);
        $natureController->setArguments([new Reference('nature_service'), new Reference('droit_service')]);

        $composanteController = $conteneur->register('composante_controller', ComposanteController::class);
        $composanteController->setArguments([new Reference('composante_service'), new Reference('droit_service')]);

        $departementController = $conteneur->register('departement_controller', DepartementController::class);
        $departementController->setArguments([new Reference('departement_service'), new Reference("etat_service"),
            new Reference("composante_service"), new Reference("service_annuel_service"),
            new Reference("droit_service"), new Reference("connexion_utilisateur")]);

        $droitController = $conteneur->register('droit_controller', DroitController::class);
        $droitController->setArguments([new Reference('droit_service')]);

        $emploiController = $conteneur->register('emploi_controller', EmploiController::class);
        $emploiController->setArguments([new Reference('emploi_service'), new Reference('droit_service')]);

        $statutController = $conteneur->register('statut_controller', StatutController::class);
        $statutController->setArguments([new Reference('statut_service'), new Reference('droit_service')]);

        $payeurController = $conteneur->register('payeur_controller', PayeurController::class);
        $payeurController->setArguments([new Reference('payeur_service')]);

        $uniteServiceAnneeController = $conteneur->register('unite_service_annee_controller', UniteServiceAnneeController::class);
        $uniteServiceAnneeController->setArguments([new Reference('unite_service_annee_service'),
            new Reference('unite_service_service'), new Reference('departement_service'),
            new Reference('service_annuel_service'), new Reference('droit_service'), new Reference('connexion_utilisateur')]);

        $etatController = $conteneur->register('etat_controller', EtatController::class);
        $etatController->setArguments([new Reference('etat_service'), new Reference('droit_service')]);

        $serviceAnnuelController = $conteneur->register('service_annuel_controller', ServiceAnnuelController::class);
        $serviceAnnuelController->setArguments([new Reference('service_annuel_service'), new Reference('droit_service'),
            new Reference('declaration_service_service'), new Reference('departement_service'),
            new Reference('intervenant_service'), new Reference('emploi_service')]);

        $intervenantService = $conteneur->register('intervenant_service', IntervenantService::class);
        $intervenantService->setArguments([new Reference("intervenant_repository"), new Reference("connexion_utilisateur")]);

        $uniteServiceService = $conteneur->register('unite_service_service', UniteServiceService::class);
        $uniteServiceService->setArguments([new Reference('unite_service_repository')]);

        $uniteServiceAnneeService = $conteneur->register('unite_service_annee_service', UniteServiceAnneeService::class);
        $uniteServiceAnneeService->setArguments([new Reference('unite_service_annee_repository')]);

        $statutService = $conteneur->register('statut_service', StatutService::class);
        $statutService->setArguments([new Reference('statut_repository')]);

        $droitService = $conteneur->register('droit_service', DroitService::class);
        $droitService->setArguments([new Reference('droit_repository'), new Reference('connexion_utilisateur')]);

        $serviceAnnuelServiceClasse = $conteneur->register('service_annuel_service', ServiceAnnuelService::class);
        $serviceAnnuelServiceClasse->setArguments([new Reference('service_annuel_repository')]);

        $emploiService = $conteneur->register('emploi_service', EmploiService::class);
        $emploiService->setArguments([new Reference('emploi_repository')]);

        $departementService = $conteneur->register('departement_service', DepartementService::class);
        $departementService->setArguments([new Reference('departement_repository'),
            new Reference('service_annuel_repository'), new Reference('intervenant_repository')]);

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

        $composanteService = $conteneur->register('composante_service', ComposanteService::class);
        $composanteService->setArguments([new Reference('composante_repository')]);

        $etatService = $conteneur->register('etat_service', EtatService::class);
        $etatService->setArguments([new Reference('etat_repository')]);

        $statutService = $conteneur->register('statut_service', StatutService::class);
        $statutService->setArguments([new Reference('statut_repository')]);

        $declarationServiceService = $conteneur->register('declaration_service_service', DeclarationServiceService::class);
        $declarationServiceService->setArguments([new Reference('declaration_service_repository'),
            new Reference('service_annuel_repository')]);

        /* Instantiation d'une collection de routes */
        $routes = new RouteCollection();

        /* Création et ajout des routes à la collection */
        $routeParDefaut = new Route("/", [
            "_controller" => ["intervenant_controller", "afficherAccueil"]
        ]);

        $routeGestion = new Route("/gestion", [
            "_controller" => ["intervenant_controller", "afficherGestion"]
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

        $routeAfficherFormulaireMiseAJourIntervenant = new Route("/mettreAJourIntervenant/{idIntervenant}", [
            "_controller" => ["intervenant_controller", "afficherFormulaireMiseAJour"]
        ]);
        $routeAfficherFormulaireMiseAJourIntervenant->setMethods(["GET"]);

        $routeMettreAJourIntervenant = new Route("/mettreAJourIntervenant/{idIntervenant}", [
            "_controller" => ["intervenant_controller", "mettreAJour"]
        ]);
        $routeMettreAJourIntervenant->setMethods(["POST"]);

        $routeAfficherListeUniteServicesAnnee = new Route("/unitesServices", [
            "_controller" => ["unite_service_annee_controller", "afficherListe"]
        ]);
        $routeAfficherListeUniteServicesAnnee->setMethods(["GET"]);

        $routeChercherUniteService = new Route("/unitesServices", [
            "_controller" => ["unite_service_controller", "chercherUniteService"]
        ]);
        $routeChercherUniteService->setMethods(["POST"]);

        $routeAfficherDetailUniteService = new Route("/unitesServices/{idUniteService}", [
            "_controller" => ["unite_service_controller", "afficherDetail"]
        ]);
        $routeAfficherDetailUniteService->setMethods(["GET"]);

        $routeExporterEnCSVIntervenant = new Route("/export/{idIntervenant}", [
            "_controller" => ["declaration_service_controller", "exporterEnCSV"]
        ]);
        $routeExporterEnCSVIntervenant->setMethods(["GET"]);

        $routeAfficherFormulaireMiseAJourUniteService = new Route("/mettreAJourUniteService/{idUniteService}", [
            "_controller" => ["unite_service_controller", "afficherFormulaireMiseAJour"]
        ]);
        $routeAfficherFormulaireMiseAJourUniteService->setMethods(["GET"]);

        $routeMettreAJourUniteService = new Route("/mettreAJourUniteService/{idUniteService}", [
            "_controller" => ["unite_service_controller", "mettreAJour"]
        ]);
        $routeMettreAJourUniteService->setMethods(["POST"]);

        $routeAfficherFormulaireCreationNature = new Route("/creerNature", [
            "_controller" => ["nature_controller", "afficherFormulaireCreation"]
        ]);
        $routeAfficherFormulaireCreationNature->setMethods(["GET"]);

        $routeCreerNatureDepuisFormulaire = new Route("/creerNature", [
            "_controller" => ["nature_controller", "creerDepuisFormulaire"]
        ]);
        $routeCreerNatureDepuisFormulaire->setMethods(["POST"]);

        $routeAfficherListeNatures = new Route("/natures", [
            "_controller" => ["nature_controller", "afficherListe"]
        ]);
        $routeAfficherListeNatures->setMethods(["GET"]);

        $routeAfficherFormulaireMiseAJourNature = new Route("/mettreAJourNature/{idNature}", [
            "_controller" => ["nature_controller", "afficherFormulaireMiseAJour"]
        ]);
        $routeAfficherFormulaireMiseAJourNature->setMethods(["GET"]);

        $routeMettreAJourNature = new Route("/mettreAJourNature/{idNature}", [
            "_controller" => ["nature_controller", "mettreAJour"]
        ]);
        $routeMettreAJourNature->setMethods(["POST"]);

        $routeSupprimerNature = new Route("/supprimerNature/{idNature}", [
            "_controller" => ["nature_controller", "supprimer"]
        ]);
        $routeSupprimerNature->setMethods(["GET"]);

        $routeAfficherFormulaireCreationComposante = new Route("/creerComposante", [
            "_controller" => ["composante_controller", "afficherFormulaireCreation"]
        ]);
        $routeAfficherFormulaireCreationComposante->setMethods(["GET"]);

        $routeCreerComposanteDepuisFormulaire = new Route("/creerComposante", [
            "_controller" => ["composante_controller", "creerDepuisFormulaire"]
        ]);
        $routeCreerComposanteDepuisFormulaire->setMethods(["POST"]);

        $routeAfficherListeComposantes = new Route("/composantes", [
            "_controller" => ["composante_controller", "afficherListe"]
        ]);
        $routeAfficherListeComposantes->setMethods(["GET"]);

        $routeAfficherFormulaireMiseAJourComposante = new Route("/mettreAJourComposante/{idComposante}", [
            "_controller" => ["composante_controller", "afficherFormulaireMiseAJour"]
        ]);
        $routeAfficherFormulaireMiseAJourComposante->setMethods(["GET"]);

        $routeMettreAJourComposante = new Route("/mettreAJourComposante/{idComposante}", [
            "_controller" => ["composante_controller", "mettreAJour"]
        ]);
        $routeMettreAJourComposante->setMethods(["POST"]);

        $routeSupprimerComposante = new Route("/supprimerComposante/{idComposante}", [
            "_controller" => ["composante_controller", "supprimer"]
        ]);
        $routeSupprimerComposante->setMethods(["GET"]);

        $routeAfficherFormulaireCreationDepartement = new Route("/creerDepartement", [
            "_controller" => ["departement_controller", "afficherFormulaireCreation"]
        ]);
        $routeAfficherFormulaireCreationDepartement->setMethods(["GET"]);

        $routeCreerDepartementDepuisFormulaire = new Route("/creerDepartement", [
            "_controller" => ["departement_controller", "creerDepuisFormulaire"]
        ]);
        $routeCreerDepartementDepuisFormulaire->setMethods(["POST"]);

        $routeAfficherListeDepartements = new Route("/departements", [
            "_controller" => ["departement_controller", "afficherListe"]
        ]);
        $routeAfficherListeDepartements->setMethods(["GET"]);

        $routeAfficherFormulaireMiseAJourDepartement = new Route("/mettreAJourDepartement/{idDepartement}", [
            "_controller" => ["departement_controller", "afficherFormulaireMiseAJour"]
        ]);
        $routeAfficherFormulaireMiseAJourDepartement->setMethods(["GET"]);

        $routeMettreAJourDepartement = new Route("/mettreAJourDepartement/{idDepartement}", [
            "_controller" => ["departement_controller", "mettreAJour"]
        ]);
        $routeMettreAJourDepartement->setMethods(["POST"]);

        $routeSupprimerDepartement = new Route("/supprimerDepartement/{idDepartement}", [
            "_controller" => ["departement_controller", "supprimer"]
        ]);
        $routeSupprimerDepartement->setMethods(["GET"]);

        $routeAfficherFormulaireCreationDroit = new Route("/creerDroit", [
            "_controller" => ["droit_controller", "afficherFormulaireCreation"]
        ]);
        $routeAfficherFormulaireCreationDroit->setMethods(["GET"]);

        $routeCreerDroitDepuisFormulaire = new Route("/creerDroit", [
            "_controller" => ["droit_controller", "creerDepuisFormulaire"]
        ]);
        $routeCreerDroitDepuisFormulaire->setMethods(["POST"]);

        $routeAfficherListeDroits = new Route("/droits", [
            "_controller" => ["droit_controller", "afficherListe"]
        ]);
        $routeAfficherListeDroits->setMethods(["GET"]);

        $routeAfficherFormulaireMiseAJourDroit = new Route("/mettreAJourDroit/{idDroit}", [
            "_controller" => ["droit_controller", "afficherFormulaireMiseAJour"]
        ]);
        $routeAfficherFormulaireMiseAJourDroit->setMethods(["GET"]);

        $routeMettreAJourDroit = new Route("/mettreAJourDroit/{idDroit}", [
            "_controller" => ["droit_controller", "mettreAJour"]
        ]);
        $routeMettreAJourDroit->setMethods(["POST"]);

        $routeSupprimerDroit = new Route("/supprimerDroit/{idDroit}", [
            "_controller" => ["droit_controller", "supprimer"]
        ]);
        $routeSupprimerDroit->setMethods(["GET"]);

        $routeAfficherFormulaireCreationEmploi = new Route("/creerEmploi", [
            "_controller" => ["emploi_controller", "afficherFormulaireCreation"]
        ]);
        $routeAfficherFormulaireCreationEmploi->setMethods(["GET"]);

        $routeCreerEmploiDepuisFormulaire = new Route("/creerEmploi", [
            "_controller" => ["emploi_controller", "creerDepuisFormulaire"]
        ]);
        $routeCreerEmploiDepuisFormulaire->setMethods(["POST"]);

        $routeAfficherListeEmplois = new Route("/emplois", [
            "_controller" => ["emploi_controller", "afficherListe"]
        ]);
        $routeAfficherListeEmplois->setMethods(["GET"]);

        $routeAfficherFormulaireMiseAJourEmploi = new Route("/mettreAJourEmploi/{idEmploi}", [
            "_controller" => ["emploi_controller", "afficherFormulaireMiseAJour"]
        ]);
        $routeAfficherFormulaireMiseAJourEmploi->setMethods(["GET"]);

        $routeMettreAJourEmploi = new Route("/mettreAJourEmploi/{idEmploi}", [
            "_controller" => ["emploi_controller", "mettreAJour"]
        ]);
        $routeMettreAJourEmploi->setMethods(["POST"]);

        $routeSupprimerEmploi = new Route("/supprimerEmploi/{idEmploi}", [
            "_controller" => ["emploi_controller", "supprimer"]
        ]);
        $routeSupprimerEmploi->setMethods(["GET"]);

        $routeAfficherFormulaireCreationStatut = new Route("/creerStatut", [
            "_controller" => ["statut_controller", "afficherFormulaireCreation"]
        ]);
        $routeAfficherFormulaireCreationStatut->setMethods(["GET"]);

        $routeCreerStatutDepuisFormulaire = new Route("/creerStatut", [
            "_controller" => ["statut_controller", "creerDepuisFormulaire"]
        ]);
        $routeCreerStatutDepuisFormulaire->setMethods(["POST"]);

        $routeAfficherListeStatuts = new Route("/statuts", [
            "_controller" => ["statut_controller", "afficherListe"]
        ]);
        $routeAfficherListeStatuts->setMethods(["GET"]);

        $routeAfficherFormulaireMiseAJourStatut = new Route("/mettreAJourStatut/{idStatut}", [
            "_controller" => ["statut_controller", "afficherFormulaireMiseAJour"]
        ]);
        $routeAfficherFormulaireMiseAJourStatut->setMethods(["GET"]);

        $routeMettreAJourStatut = new Route("/mettreAJourStatut/{idStatut}", [
            "_controller" => ["statut_controller", "mettreAJour"]
        ]);
        $routeMettreAJourStatut->setMethods(["POST"]);

        $routeSupprimerStatut = new Route("/supprimerStatut/{idStatut}", [
            "_controller" => ["statut_controller", "supprimer"]
        ]);
        $routeSupprimerStatut->setMethods(["GET"]);

        $routeAfficherFormulaireCreationPayeur = new Route("/creerPayeur", [
            "_controller" => ["payeur_controller", "afficherFormulaireCreation"]
        ]);
        $routeAfficherFormulaireCreationPayeur->setMethods(["GET"]);

        $routeCreerPayeurDepuisFormulaire = new Route("/creerPayeur", [
            "_controller" => ["payeur_controller", "creerDepuisFormulaire"]
        ]);
        $routeCreerPayeurDepuisFormulaire->setMethods(["POST"]);

        $routeAfficherListePayeurs = new Route("/payeurs", [
            "_controller" => ["payeur_controller", "afficherListe"]
        ]);
        $routeAfficherListePayeurs->setMethods(["GET"]);

        $routeAfficherFormulaireMiseAJourPayeur = new Route("/mettreAJourPayeur/{idPayeur}", [
            "_controller" => ["payeur_controller", "afficherFormulaireMiseAJour"]
        ]);
        $routeAfficherFormulaireMiseAJourPayeur->setMethods(["GET"]);

        $routeMettreAJourPayeur = new Route("/mettreAJourPayeur/{idPayeur}", [
            "_controller" => ["payeur_controller", "mettreAJour"]
        ]);
        $routeMettreAJourPayeur->setMethods(["POST"]);

        $routeSupprimerPayeur = new Route("/supprimerPayeur/{idPayeur}", [
            "_controller" => ["payeur_controller", "supprimer"]
        ]);
        $routeSupprimerPayeur->setMethods(["GET"]);

        $routeAfficherFormulaireMiseAJourUniteServiceAnnee = new Route("/mettreAJourUniteServiceAnnee/{idUniteServiceAnnee}", [
            "_controller" => ["unite_service_annee_controller", "afficherFormulaireMiseAJour"]
        ]);
        $routeAfficherFormulaireMiseAJourUniteServiceAnnee->setMethods(["GET"]);

        $routeMettreAJourUniteServiceAnnee = new Route("/mettreAJourUniteServiceAnnee/{idUniteServiceAnnee}", [
            "_controller" => ["unite_service_annee_controller", "mettreAJour"]
        ]);
        $routeMettreAJourUniteServiceAnnee->setMethods(["POST"]);

        $routeAfficherListeEtats = new Route("/etats", [
            "_controller" => ["etat_controller", "afficherListe"]
        ]);
        $routeAfficherListeEtats->setMethods(["GET"]);

        $routeAfficherFormulaireMiseAJourEtat = new Route("/mettreAJourEtat/{idEtat}", [
            "_controller" => ["etat_controller", "afficherFormulaireMiseAJour"]
        ]);
        $routeAfficherFormulaireMiseAJourEtat->setMethods(["GET"]);

        $routeMettreAJourEtat = new Route("/mettreAJourEtat/{idEtat}", [
            "_controller" => ["etat_controller", "mettreAJour"]
        ]);
        $routeMettreAJourEtat->setMethods(["POST"]);

        $routeDeconnexion = new Route("/deconnexion", [
            "_controller" => ["intervenant_controller", "deconnecter"]
        ]);
        $routeDeconnexion->setMethods(["GET"]);

        $routeChangementGestion = new Route("/changementGestion", [
            "_controller" => ["intervenant_controller", "changementGestion"]
        ]);
        $routeChangementGestion->setMethods(["POST"]);

        $routeAfficherFormulaireGestionEtat = new Route("/gererEtat", [
            "_controller" => ["departement_controller", "afficherFormulaireGestionEtat"]
        ]);
        $routeAfficherFormulaireGestionEtat->setMethods(["GET"]);

        $routeGererEtat = new Route("/gererEtat", [
            "_controller" => ["departement_controller", "gererEtat"]
        ]);
        $routeGererEtat->setMethods(["POST"]);

        $routeServices = new Route("/services", [
            "_controller" => ["intervenant_controller", "afficherServices"]
        ]);
        $routeServices->setMethods(["GET"]);

        $routeAide = new Route("/aide", [
            "_controller" => ["intervenant_controller", "afficherAide"]
        ]);
        $routeAide->setMethods(["GET"]);

        $routeAfficherFormulaireMiseAJourServiceAnnuel = new Route("/mettreAJourServiceAnnuel/{idServiceAnnuel}", [
            "_controller" => ["service_annuel_controller", "afficherFormulaireMiseAJour"]
        ]);
        $routeAfficherFormulaireMiseAJourServiceAnnuel->setMethods(["GET"]);

        $routeMettreAJourServiceAnnuel = new Route("/mettreAJourServiceAnnuel/{idServiceAnnuel}", [
            "_controller" => ["service_annuel_controller", "mettreAJour"]
        ]);
        $routeMettreAJourServiceAnnuel->setMethods(["POST"]);

        $routeMettreAJourDeclarationServicePourUnIntervenant = new Route("/mettreAJourDeclarationService/{idIntervenant}", [
            "_controller" => ["declaration_service_controller", "mettreAJourPourUnIntervenant"]
        ]);
        $routeMettreAJourDeclarationServicePourUnIntervenant->setMethods(["POST"]);

        $routeVoeux = new Route("/voeux", [
            "_controller" => ["intervenant_controller", "afficherVoeux"]
        ]);
        $routeVoeux->setMethods(["GET"]);

        /* Ajoute les routes dans la collection et leur associe un nom */
        $routes->add("accueil", $routeParDefaut);
        $routes->add("gestion", $routeGestion);
        $routes->add("afficherListeIntervenants", $routeAfficherListeIntervenants);
        $routes->add("afficherListeUnitesServicesAnnee", $routeAfficherListeUniteServicesAnnee);
        $routes->add("afficherDetailIntervenant", $routeAfficherDetailIntervenant);
        $routes->add("exporterEnCSV", $routeExporterEnCSVIntervenant);
        $routes->add("afficherDetailUniteService", $routeAfficherDetailUniteService);
        $routes->add("chercherIntervenant", $routeChercherIntervenant);
        $routes->add("chercherUniteService", $routeChercherUniteService);
        $routes->add("afficherFormulaireCreationIntervenant", $routeAfficherFormulaireCreationIntervenant);
        $routes->add("creerIntervenantDepuisFormulaire", $routeCreerIntervenantDepuisFormulaire);
        $routes->add("afficherFormulaireCreationUniteService", $routeAfficherFormulaireCreationUniteService);
        $routes->add("creerUniteServiceDepuisFormulaire", $routeCreerUniteServiceDepuisFormulaire);
        $routes->add("afficherFormulaireCreationNature", $routeAfficherFormulaireCreationNature);
        $routes->add("creerNatureDepuisFormulaire", $routeCreerNatureDepuisFormulaire);
        $routes->add("afficherListeNatures", $routeAfficherListeNatures);
        $routes->add("afficherFormulaireMiseAJourNature", $routeAfficherFormulaireMiseAJourNature);
        $routes->add("mettreAJourNature", $routeMettreAJourNature);
        $routes->add("supprimerNature", $routeSupprimerNature);
        $routes->add("afficherFormulaireCreationComposante", $routeAfficherFormulaireCreationComposante);
        $routes->add("creerComposanteDepuisFormulaire", $routeCreerComposanteDepuisFormulaire);
        $routes->add("afficherListeComposantes", $routeAfficherListeComposantes);
        $routes->add("afficherFormulaireMiseAJourComposante", $routeAfficherFormulaireMiseAJourComposante);
        $routes->add("mettreAJourComposante", $routeMettreAJourComposante);
        $routes->add("supprimerComposante", $routeSupprimerComposante);
        $routes->add("afficherFormulaireCreationDepartement", $routeAfficherFormulaireCreationDepartement);
        $routes->add("creerDepartementDepuisFormulaire", $routeCreerDepartementDepuisFormulaire);
        $routes->add("afficherListeDepartements", $routeAfficherListeDepartements);
        $routes->add("afficherFormulaireMiseAJourDepartement", $routeAfficherFormulaireMiseAJourDepartement);
        $routes->add("mettreAJourDepartement", $routeMettreAJourDepartement);
        $routes->add("supprimerDepartement", $routeSupprimerDepartement);
        $routes->add("afficherFormulaireCreationDroit", $routeAfficherFormulaireCreationDroit);
        $routes->add("creerDroitDepuisFormulaire", $routeCreerDroitDepuisFormulaire);
        $routes->add("afficherListeDroits", $routeAfficherListeDroits);
        $routes->add("afficherFormulaireMiseAJourDroit", $routeAfficherFormulaireMiseAJourDroit);
        $routes->add("mettreAJourDroit", $routeMettreAJourDroit);
        $routes->add("supprimerDroit", $routeSupprimerDroit);
        $routes->add("afficherFormulaireCreationEmploi", $routeAfficherFormulaireCreationEmploi);
        $routes->add("creerEmploiDepuisFormulaire", $routeCreerEmploiDepuisFormulaire);
        $routes->add("afficherListeEmplois", $routeAfficherListeEmplois);
        $routes->add("afficherFormulaireMiseAJourEmploi", $routeAfficherFormulaireMiseAJourEmploi);
        $routes->add("mettreAJourEmploi", $routeMettreAJourEmploi);
        $routes->add("supprimerEmploi", $routeSupprimerEmploi);
        $routes->add("afficherFormulaireCreationStatut", $routeAfficherFormulaireCreationStatut);
        $routes->add("creerStatutDepuisFormulaire", $routeCreerStatutDepuisFormulaire);
        $routes->add("afficherListeStatuts", $routeAfficherListeStatuts);
        $routes->add("afficherFormulaireMiseAJourStatut", $routeAfficherFormulaireMiseAJourStatut);
        $routes->add("mettreAJourStatut", $routeMettreAJourStatut);
        $routes->add("supprimerStatut", $routeSupprimerStatut);
        $routes->add("afficherFormulaireCreationPayeur", $routeAfficherFormulaireCreationPayeur);
        $routes->add("creerPayeurDepuisFormulaire", $routeCreerPayeurDepuisFormulaire);
        $routes->add("afficherListePayeurs", $routeAfficherListePayeurs);
        $routes->add("afficherFormulaireMiseAJourPayeur", $routeAfficherFormulaireMiseAJourPayeur);
        $routes->add("mettreAJourPayeur", $routeMettreAJourPayeur);
        $routes->add("supprimerPayeur", $routeSupprimerPayeur);
        $routes->add("afficherFormulaireMiseAJourIntervenant", $routeAfficherFormulaireMiseAJourIntervenant);
        $routes->add("mettreAJourIntervenant", $routeMettreAJourIntervenant);
        $routes->add("afficherFormulaireMiseAJourUniteService", $routeAfficherFormulaireMiseAJourUniteService);
        $routes->add("mettreAJourUniteService", $routeMettreAJourUniteService);
        $routes->add("afficherFormulaireMiseAJourUniteServiceAnnee", $routeAfficherFormulaireMiseAJourUniteServiceAnnee);
        $routes->add("mettreAJourUniteServiceAnnee", $routeMettreAJourUniteServiceAnnee);
        $routes->add("afficherListeEtats", $routeAfficherListeEtats);
        $routes->add("afficherFormulaireMiseAJourEtat", $routeAfficherFormulaireMiseAJourEtat);
        $routes->add("mettreAJourEtat", $routeMettreAJourEtat);
        $routes->add("deconnexion", $routeDeconnexion);
        $routes->add("changementGestion", $routeChangementGestion);
        $routes->add("afficherFormulaireGestionEtat", $routeAfficherFormulaireGestionEtat);
        $routes->add("gererEtat", $routeGererEtat);
        $routes->add("services", $routeServices);
        $routes->add("aide", $routeAide);
        $routes->add("afficherFormulaireMiseAJourServiceAnnuel", $routeAfficherFormulaireMiseAJourServiceAnnuel);
        $routes->add("mettreAJourServiceAnnuel", $routeMettreAJourServiceAnnuel);
        $routes->add("afficherFormulaireMiseAJourServiceAnnuel", $routeAfficherFormulaireMiseAJourServiceAnnuel);
        $routes->add("mettreAJourServiceAnnuel", $routeMettreAJourServiceAnnuel);
        $routes->add("mettreAJourDeclarationServicePourUnIntervenant", $routeMettreAJourDeclarationServicePourUnIntervenant);
        $routes->add("voeux", $routeVoeux);

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


        $serviceAnnuelServiceClasse = new ServiceAnnuelService(new ServiceAnnuelRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB())));
        $departementServiceClasse = new DepartementService(new DepartementRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB())),
            new ServiceAnnuelRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB())),
            new IntervenantRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB())));
        $connexionUtilisateurClasse = new ConnexionUtilisateur(new IntervenantRepository(new ConnexionBaseDeDonnees(new ConfigurationBDDMariaDB())));

        $serviceAnnuel = $serviceAnnuelServiceClasse->recupererParIntervenantAnnuelPlusRecent($connexionUtilisateurClasse->getIdUtilisateurConnecte());
        $depActuel = $departementServiceClasse->recupererParIdentifiant($serviceAnnuel->getIdDepartement())->getLibDepartement();
        $anneeActuelle = $serviceAnnuel->getMillesime();

        $dep = InfosGlobales::lireDepartement() ?? $depActuel;
        $idEtat = $departementServiceClasse->recupererParLibelle($dep)->getIdEtat();

        /* Ajout de variables globales */
        $twig->addGlobal('messagesFlash', new MessageFlash());
        $twig->addGlobal('connexionUtilisateur', $connexionUtilisateurClasse);
        $twig->addGlobal('departementActuel', $dep);
        $twig->addGlobal('anneeActuelle', InfosGlobales::lireAnnee() ?? $anneeActuelle);
        $twig->addGlobal("idEtat", $idEtat);

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
             * @throws LogicException If a controller was found based on the request but it is not callable
             */
            $controleur = $resolveurDeControleur->getController($requete);

            /* Instanciation d'un résolveur d'argument, sert à récupérer l'argument placé en paramètre d'une action de controleur */
            $resolveurDArguments = new ArgumentResolver();

            /**
             * @throws RuntimeException When no value could be provided for a required argument
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
    public static function getConteneur(): mixed
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