<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\ConnexionUtilisateurInterface;
use App\Sensei\Lib\InfosGlobales;
use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\AppartenirServiceInterface;
use App\Sensei\Service\DeclarationServiceServiceInterface;
use App\Sensei\Service\DepartementServiceInterface;
use App\Sensei\Service\DroitServiceInterface;
use App\Sensei\Service\EmploiServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\IntervenantServiceInterface;
use App\Sensei\Service\InterventionServiceInterface;
use App\Sensei\Service\ResponsableUSServiceInterface;
use App\Sensei\Service\SaisonServiceInterface;
use App\Sensei\Service\SemestreServiceInterface;
use App\Sensei\Service\ServiceAnnuelServiceInterface;
use App\Sensei\Service\StatutServiceInterface;
use App\Sensei\Service\UniteServiceAnneeServiceInterface;
use App\Sensei\Service\UniteServiceServiceInterface;
use App\Sensei\Service\VoeuServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use TypeError;

class IntervenantController extends GenericController
{

    public function __construct(
        private readonly IntervenantServiceInterface        $intervenantService,
        private readonly StatutServiceInterface             $statutService,
        private readonly DroitServiceInterface              $droitService,
        private readonly ServiceAnnuelServiceInterface      $serviceAnnuelService,
        private readonly EmploiServiceInterface             $emploiService,
        private readonly DepartementServiceInterface        $departementService,
        private readonly UniteServiceServiceInterface       $uniteServiceService,
        private readonly UniteServiceAnneeServiceInterface  $uniteServiceAnneeService,
        private readonly VoeuServiceInterface               $voeuService,
        private readonly ResponsableUSServiceInterface      $responsableUSService,
        private readonly DeclarationServiceServiceInterface $declarationServiceService,
        private readonly ConnexionUtilisateurInterface      $connexionUtilisateur,
        private readonly SaisonServiceInterface $saisonService,
        private readonly SemestreServiceInterface $semestreService,
        private readonly AppartenirServiceInterface $appartenirService
    )
    {
    }

    /**
     * Méthode qui affiche la liste des utilisateurs.
     *
     * @return Response
     * @throws ServiceException
     */
    public function afficherListe(): Response
    {
        try {
            $serviceAnnuel = $this->serviceAnnuelService->recupererParIntervenantAnnuelPlusRecent($this->connexionUtilisateur->getIdUtilisateurConnecte());
            $idDepartement = $this->departementService->recupererParLibelle(InfosGlobales::lireDepartement() ?? "MATH")->getIdDepartement() ?? $serviceAnnuel->getIdDepartement();
            $annee = $serviceAnnuel->getMillesime();
            $anneeActuelle = InfosGlobales::lireAnnee() ?? $annee;

            $intervenantsAnnuelsEtDuDepartementNonVacataire = $this->intervenantService->recupererIntervenantsAvecAnneeEtDepartementNonVacataire($anneeActuelle, $idDepartement);
            $intervenantsAnnuelsEtDuDepartementVacataire = $this->intervenantService->recupererIntervenantsAvecAnneeEtDepartementVacataire($anneeActuelle, $idDepartement);
            return IntervenantController::afficherTwig("intervenant/listeIntervenants.twig", [
                "intervenantsAnnuelsEtDuDepartementNonVacataire" => $intervenantsAnnuelsEtDuDepartementNonVacataire,
                "intervenantsAnnuelsEtDuDepartementVacataire" => $intervenantsAnnuelsEtDuDepartementVacataire]);

        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("accueil");
        }
    }

    /**
     * Méthode qui affiche le détail d'un intervenant.
     *
     * @param int $idIntervenant
     * @return Response
     */
    public function afficherDetail(int $idIntervenant): Response
    {
        try {
            $intervenant = $this->intervenantService->recupererParIdentifiant($idIntervenant);
            $statut = $this->statutService->recupererParIdentifiant($intervenant->getIdStatut());
            $droit = $this->droitService->recupererParIdentifiant($intervenant->getIdDroit());

            $servicesAnnuels = $this->serviceAnnuelService->recupererParIntervenant($idIntervenant);

            $emplois = [];
            $departements = [];
            $declarationsServices = $this->declarationServiceService->recupererVueParIdIntervenant($idIntervenant);
            $declarationsServicesAnnuels = [];
            foreach ($servicesAnnuels as $serviceAnnuel) {
                $emplois[] = $this->emploiService->recupererParIdentifiant($serviceAnnuel->getIdEmploi());
                $departements[] = $this->departementService->recupererParIdentifiant($serviceAnnuel->getIdDepartement());

                $declarationsServicesParAnnee = [];
                $declarationsServicesAvecMemeId = [];
                $i = 0;
                foreach ($declarationsServices as $declarationService) {
                    if ($declarationService["millesime"] == $serviceAnnuel->getMillesime()) {
                        if ($i != 0) {
                            if ($declarationService["idUsReferentiel"] != $declarationsServicesAvecMemeId[0]["idUsReferentiel"]) {
                                $declarationsServicesParAnnee[] = $declarationsServicesAvecMemeId;
                                $declarationsServicesAvecMemeId = [];
                                $i = 0;
                            }
                        }
                        $declarationsServicesAvecMemeId[] = $declarationService;
                        $i++;
                    } else {
                        if (count($declarationsServicesAvecMemeId) > 0){
                            $declarationsServicesParAnnee[] = $declarationsServicesAvecMemeId;
                            $declarationsServicesAvecMemeId = [];
                        }
                    }
                }
                if (count($declarationsServicesAvecMemeId) > 0){
                    $declarationsServicesParAnnee[] = $declarationsServicesAvecMemeId;
                }
                $declarationsServicesAnnuels[] = $declarationsServicesParAnnee;
            }

            $parametres = [
                "intervenant" => $intervenant,
                "statut" => $statut,
                "droit" => $droit,
                "servicesAnnuels" => $servicesAnnuels,
                "emplois" => $emplois,
                "departements" => $departements,
                "declarationsServicesAnnuels" => $declarationsServicesAnnuels
            ];

            return IntervenantController::afficherTwig("intervenant/detailIntervenant.twig", $parametres);
        } catch (ServiceException|TypeError $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("afficherListeIntervenants");
        }
    }

    public function afficherFormulaireCreation(): Response
    {
        try {
            $this->droitService->verifierDroits();
            $statuts = $this->statutService->recupererStatuts();
            $droits = $this->droitService->recupererDroits();
            $idDroitUtilisateur = $this->connexionUtilisateur->getIntervenantConnecte()->getIdDroit();
            $departements = $this->departementService->recupererDepartements();
            $emplois = $this->emploiService->recupererEmplois();

            return IntervenantController::afficherTwig("intervenant/creationIntervenant.twig",
                [
                    "statuts" => $statuts,
                    "droits" => $droits,
                    "idDroitUtilisateur" => $idDroitUtilisateur,
                    "departements" => $departements,
                    "emplois" => $emplois
                ]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("afficherListeIntervenants");
        }

    }

    public function creerDepuisFormulaire(): Response
    {
        try {
            $nom = $_POST["nom"];
            $prenom = $_POST["prenom"];
            $idIntervenantReferentiel = $_POST["idIntervenantReferentiel"];
            $emailInstitutionnel = $_POST["emailInstitutionnel"];
            $emailUsage = $_POST["emailUsage"];



            $intervenant = [
                "nom" => strcmp($nom, "") == 0 ? null : $nom,
                "prenom" => strcmp($prenom, "") == 0 ? null : $prenom,
                "idIntervenantReferentiel" => strcmp($idIntervenantReferentiel, "") == 0 ? null : $idIntervenantReferentiel,
                "idStatut" => $_POST["statut"],
                "idDroit" => $_POST["droit"],
                "emailInstitutionnel" => strcmp($emailInstitutionnel, "") == 0 ? null : $emailInstitutionnel,
                "emailUsage" => strcmp($emailUsage, "") == 0 ? null : $emailUsage,
                "deleted" => 0
            ];

            $this->intervenantService->creerIntervenant($intervenant);
            //On va également créer son service
            $idDepartement = $_POST["idDepartement"];

            $serviceAnnuel = $this->serviceAnnuelService->recupererPlusRecentDuDepartement($idDepartement);
            $annee = $serviceAnnuel->getMillesime();

            if (isset($idIntervenantReferentiel)){
                $intervenant = $this->intervenantService->recupererParUID($idIntervenantReferentiel);
            } else {
                $intervenant = $this->intervenantService->recupererParEmailInstitutionnel($emailInstitutionnel);
            }

            $serviceAnnuel = [
                "idServiceAnnuel" => 0,
                "idDepartement" => $idDepartement,
                "idIntervenant" => $intervenant->getIdIntervenant(),
                "millesime" => $annee,
                "idEmploi" => $_POST["idEmploi"],
                "serviceStatuaire" => $_POST["serviceStatuaire"],
                "serviceFait" => 0,
                "delta" => 0,
                "deleted" => 0
            ];
            $this->serviceAnnuelService->creerServiceAnnuel($serviceAnnuel);

            MessageFlash::ajouter("success", "L'intervenant a bien été créé !");
        } catch (ServiceException $exception){
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return IntervenantController::rediriger("accueil");
    }

    public function afficherAccueil(): Response
    {
        try {
            $serviceAnnuel = $this->serviceAnnuelService->recupererParIntervenantAnnuelPlusRecent($this->connexionUtilisateur->getIdUtilisateurConnecte());
            $idDepartement = $this->departementService->recupererParLibelle(InfosGlobales::lireDepartement() ?? "MATH")->getIdDepartement() ?? $serviceAnnuel->getIdDepartement();
            $annee = $serviceAnnuel->getMillesime();

            $anneeActuelle = InfosGlobales::lireAnnee() ?? $annee;
            $idEtat = $this->departementService->recupererParIdentifiant($idDepartement)->getIdEtat();

            $intervenantConnecte = $this->connexionUtilisateur->getIntervenantConnecte();
            $serviceAnnuel = $this->serviceAnnuelService->recupererParIntervenantAnnuel($intervenantConnecte->getIdIntervenant(), $anneeActuelle);
            if (isset($serviceAnnuel)) {
                $responsabilitesAnnuel = $this->responsableUSService->recupererParIdIntervenantAnnuel($intervenantConnecte->getIdIntervenant(), $serviceAnnuel->getMillesime());
            } else {
                $responsabilitesAnnuel = [];
            }

            $us = [];
            $usa = [];
            $i = 0;
            foreach ($responsabilitesAnnuel as $responsabilite) {
                $usa[] = $this->uniteServiceAnneeService->recupererParIdentifiant($responsabilite->getIdUniteServiceAnnee());
                $us[] = $this->uniteServiceService->recupererParIdentifiant($usa[$i]->getIdUniteService());
                $i++;
            }

            $parametres = [
                "utilisateur" => $intervenantConnecte,
                "responsabilites" => $responsabilitesAnnuel,
                "unitesServicesAnnees" => $usa,
                "unitesServices" => $us,
                "idEtat" => $idEtat
            ];

            return IntervenantController::afficherTwig("accueil.twig", $parametres);
        } catch (ServiceException|TypeError $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("afficherListeIntervenants");
        }
    }

    public function afficherGestion(): Response
    {
        try {
            $departements = $this->departementService->recupererDepartements();
            $anneeReference = $this->serviceAnnuelService->recupererParIntervenantAnnuelPlusRecent($this->connexionUtilisateur->getIdUtilisateurConnecte())
                ->getMillesime();
            return IntervenantController::afficherTwig("gestion.twig", [
                "departements" => $departements,
                "anneeReference" => $anneeReference
            ]);
        } catch (ServiceException $exception){
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("accueil");
        }

    }

    public function chercherIntervenant(): Response
    {
        try {
            $recherche = $_POST["intervenant"];
            $intervenant = $this->intervenantService->rechercherIntervenant($recherche);
            return IntervenantController::rediriger("afficherDetailIntervenant", ["idIntervenant" => $intervenant->getIdIntervenant()]);
        } catch (ServiceException|TypeError $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("afficherListeIntervenants");
        }
    }

    public function afficherFormulaireMiseAJour(int $idIntervenant): Response
    {
        try {
            $this->droitService->verifierDroits();
            $intervenant = $this->intervenantService->recupererParIdentifiant($idIntervenant);
            $statuts = $this->statutService->recupererStatuts();
            $droits = $this->droitService->recupererDroits();
            return IntervenantController::afficherTwig("intervenant/mettreAJour.twig", [
                "intervenant" => $intervenant,
                "statuts" => $statuts,
                "droits" => $droits]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("afficherListeIntervenants");
        }
    }

    public function mettreAJour(): Response
    {
        try {
            $intervenant = [
                "idIntervenant" => $_POST["idIntervenant"],
                "nom" => strcmp($_POST["nom"], "") == 0 ? null : $_POST["nom"],
                "prenom" => strcmp($_POST["prenom"], "") == 0 ? null : $_POST["prenom"],
                "idIntervenantReferentiel" => strcmp($_POST["idIntervenantReferentiel"], "") == 0 ? null : $_POST["idIntervenantReferentiel"],
                "idStatut" => $_POST["statut"],
                "idDroit" => $_POST["droit"],
                "emailInstitutionnel" => strcmp($_POST["emailInstitutionnel"], "") == 0 ? null : $_POST["emailInstitutionnel"],
                "emailUsage" => strcmp($_POST["emailUsage"], "") == 0 ? null : $_POST["emailUsage"],
                "deleted" => 0
            ];
            $this->intervenantService->modifierIntervenant($intervenant);
            MessageFlash::ajouter("success", "L'intervenant a bien été modifié !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return IntervenantController::rediriger("accueil");
    }

    /**
     * @throws ServiceException
     */
    public function deconnecter(): Response
    {
        if (!$this->connexionUtilisateur->estConnecte()) {
            throw new ServiceException("Utilisateur non connecté !");
        }
        $this->connexionUtilisateur->deconnecter();
        InfosGlobales::supprimerInfos();
        return IntervenantController::rediriger("accueil");
    }

    public function changementGestion(): Response
    {
        $annee = $_POST["annee"];
        $departement = $this->departementService->recupererParIdentifiant($_POST["departement"])->getLibDepartement();

        InfosGlobales::enregistrerDepartement($departement);
        InfosGlobales::enregistrerAnnee($annee);

        return IntervenantController::rediriger("accueil");
    }

    public function afficherServices(): Response{
        try {
            $serviceAnnuel = $this->serviceAnnuelService->recupererParIntervenantAnnuelPlusRecent($this->connexionUtilisateur->getIdUtilisateurConnecte());
            $idDepartement = $this->departementService->recupererParLibelle(InfosGlobales::lireDepartement() ?? "MATH")->getIdDepartement() ?? $serviceAnnuel->getIdDepartement();
            $annee = $serviceAnnuel->getMillesime();
            $anneeActuelle = InfosGlobales::lireAnnee() ?? $annee;

            $intervenantsAnnuelsEtDuDepartementNonVacataire = $this->intervenantService->recupererIntervenantsAvecAnneeEtDepartementNonVacataire($anneeActuelle, $idDepartement);
            $intervenantsAnnuelsEtDuDepartementVacataire = $this->intervenantService->recupererIntervenantsAvecAnneeEtDepartementVacataire($anneeActuelle, $idDepartement);

            $servicesAnnuelsNonVacataires = [];
            $declarationsServicesNonVacataires = [];
            foreach ($intervenantsAnnuelsEtDuDepartementNonVacataire as $intervenantNonVacataire){
                $servicesAnnuelsNonVacataires[] = $this->serviceAnnuelService->recupererParIntervenantAnnuel($intervenantNonVacataire->getIdIntervenant(), $anneeActuelle);
                $declarationsServices = $this->declarationServiceService->recupererVueParIdIntervenantAnnuel($intervenantNonVacataire->getIdIntervenant(), $anneeActuelle);

                $declarationsServicesParIntervenant = [];
                $declarationsServicesAvecMemeId = [];
                $i = 0;
                foreach ($declarationsServices as $declarationService) {
                    if ($i != 0) {
                        if ($declarationService["idUsReferentiel"] != $declarationsServicesAvecMemeId[0]["idUsReferentiel"]) {
                            $declarationsServicesParIntervenant[] = $declarationsServicesAvecMemeId;
                            $declarationsServicesAvecMemeId = [];
                            $i = 0;
                        }
                    }
                    $declarationsServicesAvecMemeId[] = $declarationService;
                    $i++;
                }
                if (count($declarationsServicesAvecMemeId) > 0){
                    $declarationsServicesParIntervenant[] = $declarationsServicesAvecMemeId;
                }
                $declarationsServicesNonVacataires[] = $declarationsServicesParIntervenant;
            }

            $servicesAnnuelsVacataires = [];
            $declarationsServicesVacataires = [];
            foreach ($intervenantsAnnuelsEtDuDepartementVacataire as $intervenantVacataire){
                $servicesAnnuelsVacataires[] = $this->serviceAnnuelService->recupererParIntervenantAnnuel($intervenantVacataire->getIdIntervenant(), $anneeActuelle);
                $declarationsServices = $this->declarationServiceService->recupererVueParIdIntervenantAnnuel($intervenantVacataire->getIdIntervenant(), $anneeActuelle);

                $declarationsServicesParIntervenant = [];
                $declarationsServicesAvecMemeId = [];
                $i = 0;
                foreach ($declarationsServices as $declarationService) {
                    if ($i != 0) {
                        if ($declarationService["idUsReferentiel"] != $declarationsServicesAvecMemeId[0]["idUsReferentiel"]) {
                            $declarationsServicesParIntervenant[] = $declarationsServicesAvecMemeId;
                            $declarationsServicesAvecMemeId = [];
                            $i = 0;
                        }
                    }
                    $declarationsServicesAvecMemeId[] = $declarationService;
                    $i++;
                }
                if (count($declarationsServicesAvecMemeId) > 0){
                    $declarationsServicesParIntervenant[] = $declarationsServicesAvecMemeId;
                }
                $declarationsServicesVacataires[] = $declarationsServicesParIntervenant;
            }

            return IntervenantController::afficherTwig("service.twig", [
                "intervenantsAnnuelsEtDuDepartementNonVacataire" => $intervenantsAnnuelsEtDuDepartementNonVacataire,
                "intervenantsAnnuelsEtDuDepartementVacataire" => $intervenantsAnnuelsEtDuDepartementVacataire,
                "servicesAnnuelsNonVacataires" => $servicesAnnuelsNonVacataires,
                "servicesAnnuelsVacataires" => $servicesAnnuelsVacataires,
                "declarationsServicesNonVacataires" => $declarationsServicesNonVacataires,
                "declarationsServicesVacataires" => $declarationsServicesVacataires]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("accueil");
        }
    }

    public function afficherVoeux(): Response
    {
        try {

            $departement = $this->departementService->recupererParLibelle(InfosGlobales::lireDepartement() ?? "MATH");
            $this->droitService->verifierDroitsPageVoeux($departement->getIdEtat());

            $serviceAnnuel = $this->serviceAnnuelService->recupererPlusRecentDuDepartement($departement->getIdDepartement());
            $anneeService = $serviceAnnuel->getMillesime();
            $anneeActuelle = InfosGlobales::lireAnnee();

            // Test pour éviter d'initialiser à chaque arrivée sur la page des voeux
            if ($anneeActuelle == $anneeService+1){

                // Nous allons regarder les DeclarationService des intervenants et voir s'ils sont tous à 0
                // S'ils sont à 0 alors l'ancienne phase de voeu n'est pas terminée.
                $estTermine = $this->declarationServiceService->verifierPhaseDeVoeu($anneeService, $departement->getIdDepartement());
                if ($estTermine){
                    $anneeService += 1;
                    $this->demarrerPhaseVoeu($departement->getIdDepartement(), $anneeService);
                }
            }
            $intervenantsAnnuelsEtDuDepartementNonVacataire = $this->intervenantService->recupererIntervenantsAvecAnneeEtDepartementNonVacataire($anneeService, $departement->getIdDepartement());
            $intervenantsAnnuelsEtDuDepartementVacataire = $this->intervenantService->recupererIntervenantsAvecAnneeEtDepartementVacataire($anneeService, $departement->getIdDepartement());
            $unitesServicesAnneesDepartement = $this->uniteServiceAnneeService->recupererUnitesServicesPourUneAnneePourUnDepartement($anneeService, $departement->getIdDepartement());
            $unitesServicesAnneesColoration = $this->uniteServiceAnneeService->recupererUnitesServicesAnneeUniquementColoration($anneeService, $departement->getIdDepartement());

            $servicesAnnuelsNonVacataires = [];
            $voeuxNonVacataires = [];
            foreach ($intervenantsAnnuelsEtDuDepartementNonVacataire as $intervenantNonVacataire){
                $servicesAnnuelsNonVacataires[] = $this->serviceAnnuelService->recupererParIntervenantAnnuel($intervenantNonVacataire->getIdIntervenant(), $anneeService);
                $voeuxIntervenants = $this->voeuService->recupererVueParIntervenantAnnuel($intervenantNonVacataire->getIdIntervenant(), $anneeService);

                $voeuxAvecMemeId = [];
                $voeuxParIntervenant = [];
                $i = 0;
                foreach ($voeuxIntervenants as $voeuIntervenant) {
                    if ($i != 0) {
                        if ($voeuIntervenant["idUSReferentiel"] != $voeuxAvecMemeId[0]["idUSReferentiel"]) {
                            $voeuxParIntervenant[] = $voeuxAvecMemeId;
                            $voeuxAvecMemeId = [];
                            $i = 0;
                        }
                    }
                    $voeuxAvecMemeId[] = $voeuIntervenant;
                    $i++;
                }
                if (count($voeuxAvecMemeId) > 0){
                    $voeuxParIntervenant[] = $voeuxAvecMemeId;
                }
                $voeuxNonVacataires[] = $voeuxParIntervenant;
            }

            $servicesAnnuelsVacataires = [];
            $voeuxVacataires = [];
            foreach ($intervenantsAnnuelsEtDuDepartementVacataire as $intervenantVacataire){
                $servicesAnnuelsVacataires[] = $this->serviceAnnuelService->recupererParIntervenantAnnuel($intervenantVacataire->getIdIntervenant(), $anneeService);
                $voeuxIntervenants[] = $this->voeuService->recupererVueParIntervenantAnnuel($intervenantVacataire->getIdIntervenant(), $anneeService);

                $voeuxAvecMemeId = [];
                $voeuxParIntervenant = [];
                $i = 0;
                foreach ($voeuxIntervenants as $voeuIntervenant) {
                    if ($i != 0) {
                        if ($voeuIntervenant["idUSReferentiel"] != $voeuxAvecMemeId[0]["idUSReferentiel"]) {
                            $voeuxParIntervenant[] = $voeuxAvecMemeId;
                            $voeuxAvecMemeId = [];
                            $i = 0;
                        }
                    }
                    $voeuxAvecMemeId[] = $voeuIntervenant;
                    $i++;
                }
                if (count($voeuxAvecMemeId) > 0){
                    $voeuxParIntervenant[] = $voeuxAvecMemeId;
                }
                $voeuxVacataires[] = $voeuxParIntervenant;
            }

            $indefinisDepartement = [];
            $indefinisColoration = [];

            $usaPrintemps = [];
            $usaAutomne = [];
            $usaAnnuel = [];
            $usPrintemps = [];
            $usAutomne = [];
            $usAnnuel = [];

            for ($i=0; $i < 11; $i++){
                $usaPrintemps[] = [];
                $usaAutomne[] = [];
                $usaAnnuel[] = [];
                $usPrintemps[] = [];
                $usAutomne[] = [];
                $usAnnuel[] = [];
            }

            foreach ($unitesServicesAnneesDepartement as $usaDepartement){
                $uniteService = $this->uniteServiceService->recupererParIdentifiant($usaDepartement->getIdUniteService());
                $semestre = $uniteService->getSemestre();
                if ($uniteService->getNature() == 1){ //Eviter les doublons DE et UR
                    switch ($uniteService->getSaison()){
                        case 0:
                            if ($semestre > 0){ // Voir la vue de la création d'une unité
                                $usaPrintemps[$semestre-1][] = $usaDepartement;
                                $usPrintemps[$semestre-1][] = $this->uniteServiceService->recupererParIdentifiant($usaDepartement->getIdUniteService());
                            } else {
                                $indefinisDepartement[] = [$usaDepartement, $this->uniteServiceService->recupererParIdentifiant($usaDepartement->getIdUniteService())];
                            }
                            break;
                        case 1:
                            if ($semestre > 0){
                                $usaAutomne[$semestre-1][] = $usaDepartement;
                                $usAutomne[$semestre-1][] = $this->uniteServiceService->recupererParIdentifiant($usaDepartement->getIdUniteService());
                            } else {
                                $indefinisDepartement[] = [$usaDepartement, $this->uniteServiceService->recupererParIdentifiant($usaDepartement->getIdUniteService())];
                            }
                            break;
                        default:
                            if ($semestre > 0){ // Voir la vue de la création d'une unité
                                $usaAnnuel[$semestre-1][] = $usaDepartement;
                                $usAnnuel[$semestre-1][] = $this->uniteServiceService->recupererParIdentifiant($usaDepartement->getIdUniteService());
                            } else {
                                $indefinisDepartement[] = [$usaDepartement, $this->uniteServiceService->recupererParIdentifiant($usaDepartement->getIdUniteService())];
                            }
                            break;
                    }
                }

            }

            $usaColorationPrintemps = [];
            $usaColorationAutomne = [];
            $usaColorationAnnuel = [];
            $usColorationPrintemps = [];
            $usColorationAutomne = [];
            $usColorationAnnuel = [];

            for ($i=0; $i < 11; $i++){
                $usaColorationPrintemps[] = [];
                $usaColorationAutomne[] = [];
                $usaColorationAnnuel[] = [];
                $usColorationPrintemps[] = [];
                $usColorationAutomne[] = [];
                $usColorationAnnuel[] = [];
            }

            foreach ($unitesServicesAnneesColoration as $usaColoration){
                $uniteService = $this->uniteServiceService->recupererParIdentifiant($usaColoration->getIdUniteService());
                $semestre = $uniteService->getSemestre();
                if ($uniteService->getNature() == 1){ //Eviter les doublons DE et UR
                    switch ($uniteService->getSaison()){
                        case 0:
                            if ($semestre > 0){ // Voir la vue de la création d'une unité
                                $usaColorationPrintemps[$semestre-1][] = $usaColoration;
                                $usColorationPrintemps[$semestre-1][] = $this->uniteServiceService->recupererParIdentifiant($usaColoration->getIdUniteService());
                            } else {
                                $indefinisColoration[] = [$usaColoration, $this->uniteServiceService->recupererParIdentifiant($usaColoration->getIdUniteService())];
                            }
                            break;
                        case 1:
                            if ($semestre > 0){
                                $usaColorationAutomne[$semestre-1][] = $usaColoration;
                                $usColorationAutomne[$semestre-1][] = $this->uniteServiceService->recupererParIdentifiant($usaColoration->getIdUniteService());
                            } else {
                                $indefinisColoration[] = [$usaColoration, $this->uniteServiceService->recupererParIdentifiant($usaColoration->getIdUniteService())];
                            }
                            break;
                        default:
                            if ($semestre > 0){ // Voir la vue de la création d'une unité
                                $usaColorationAnnuel[$semestre-1][] = $usaColoration;
                                $usColorationAnnuel[$semestre-1][] = $this->uniteServiceService->recupererParIdentifiant($usaColoration->getIdUniteService());
                            } else {
                                $indefinisColoration[] = [$usaColoration, $this->uniteServiceService->recupererParIdentifiant($usaColoration->getIdUniteService())];
                            }
                            break;
                    }
                }

            }

            $referentiels = [];
            $decharges = [];

            $ur = $this->uniteServiceAnneeService->recupererReferentiels($anneeService);
            foreach ($ur as $unite){
                $referentiels[] = [$unite, $this->uniteServiceService->recupererParIdentifiant($unite->getIdUniteService())];
            }

            $de = $this->uniteServiceAnneeService->recupererDecharges($anneeService);
            foreach ($de as $unite){
                $decharges[] = [$unite, $this->uniteServiceService->recupererParIdentifiant($unite->getIdUniteService())];
            }



            return IntervenantController::afficherTwig("voeu.twig", [
                "intervenantsAnnuelsEtDuDepartementNonVacataire" => $intervenantsAnnuelsEtDuDepartementNonVacataire,
                "intervenantsAnnuelsEtDuDepartementVacataire" => $intervenantsAnnuelsEtDuDepartementVacataire,
                "servicesAnnuelsNonVacataires" => $servicesAnnuelsNonVacataires,
                "servicesAnnuelsVacataires" => $servicesAnnuelsVacataires,
                "voeuxNonVacataires" => $voeuxNonVacataires,
                "voeuxVacataires" => $voeuxVacataires,
                "anneeService" => $anneeService,
                "usaPrintemps" => $usaPrintemps,
                "usaAutomne" => $usaAutomne,
                "usaAnnuel" => $usaAnnuel,
                "usPrintemps" => $usPrintemps,
                "usAutomne" => $usAutomne,
                "usAnnuel" => $usAnnuel,
                "usaColorationPrintemps" => $usaColorationPrintemps,
                "usaColorationAutomne" => $usaColorationAutomne,
                "usaColorationAnnuel" => $usaColorationAnnuel,
                "usColorationPrintemps" => $usColorationPrintemps,
                "usColorationAutomne" => $usColorationAutomne,
                "usColorationAnnuel" => $usColorationAnnuel,
                "decharges" => $decharges,
                "referentiels" => $referentiels,
                "indefinisDepartement" => $indefinisDepartement,
                "indefinisColoration" => $indefinisColoration
            ]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("accueil");
        }
    }

    /**
     * Nous allons initialiser la page des voeux si c'est pour une nouvelle année, sinon il ne se
     * passera rien
     *
     * @param int $idDepartement
     * @param int $annee
     * @return void
     * @throws ServiceException
     */
    private function demarrerPhaseVoeu(int $idDepartement, int $annee){
        $intervenantsAnnuelsEtDuDepartementNonVacataire = $this->intervenantService->recupererIntervenantsAvecAnneeEtDepartementNonVacataire($annee, $idDepartement);
        $intervenantsAnnuelsEtDuDepartementVacataire = $this->intervenantService->recupererIntervenantsAvecAnneeEtDepartementVacataire($annee, $idDepartement);
        $unitesServicesAnneesDuDepartement = $this->uniteServiceAnneeService->recupererUnitesServicesPourUneAnneePourUnDepartement($annee, $idDepartement);
        $unitesServicesAnneesColoration = $this->uniteServiceAnneeService->recupererUnitesServicesAnneeUniquementColoration($annee, $idDepartement);

        // Creation des services annuels pour la nouvelle année
        if (count($intervenantsAnnuelsEtDuDepartementNonVacataire) == 0 && count($intervenantsAnnuelsEtDuDepartementVacataire) == 0){
            $servicesAnnuelsPrecedent = $this->serviceAnnuelService->recupererParDepartementAnnuel($idDepartement, $annee-1);
            foreach ($servicesAnnuelsPrecedent as $serviceAnnuelPrecedent){
                $this->serviceAnnuelService->renouvelerServiceAnnuel($serviceAnnuelPrecedent, $annee);
            }
        }

        if (count($unitesServicesAnneesDuDepartement) == 0 && count($unitesServicesAnneesColoration) == 0) {
            $unitesServicesAnneesDuDepartementPrecedent = $this->uniteServiceAnneeService->recupererUnitesServicesPourUneAnneePourUnDepartement($annee-1, $idDepartement);
            $unitesServicesAnneesColorationPrecedent = $this->uniteServiceAnneeService->recupererUnitesServicesAnneeUniquementColoration($annee-1, $idDepartement);
            foreach ($unitesServicesAnneesDuDepartementPrecedent as $usa){
                $us = $this->uniteServiceService->recupererParIdentifiant($usa->getIdUniteService());
                if ($us->getAnneeCloture() > $annee){
                    $this->uniteServiceAnneeService->renouvelerUniteServiceAnnee($usa, $annee);
                }
            }
            foreach ($unitesServicesAnneesColorationPrecedent as $usa){
                $us = $this->uniteServiceService->recupererParIdentifiant($usa->getIdUniteService());
                if ($us->getAnneeCloture() > $annee){
                    $this->uniteServiceAnneeService->renouvelerUniteServiceAnnee($usa, $annee);
                }
            }

            // Ouverture des unités de services de la nouvelle année
            $usOuverture = $this->uniteServiceService->recupererParAnneeOuverture($annee);
            foreach ($usOuverture as $us) {
                if ($this->appartenirService->verifierAppartenance($us->getIdUniteService(), $idDepartement)){
                    $usa = [
                        "idDepartement" => $idDepartement,
                        "idUniteService" => $us->getIdUniteService(),
                        "libUSA" => $us->getLibUS(),
                        "millesime" => $annee,
                        "heuresCM" => $us->getHeuresCM(),
                        "nbGroupesCM" => 0,
                        "heuresTD" => $us->getHeuresTD(),
                        "nbGroupesTD" => 0,
                        "heuresTP" => $us->getHeuresTP(),
                        "nbGroupesTP" => 0,
                        "heuresStage" => $us->getHeuresStage(),
                        "nbGroupesStage" => 0,
                        "heuresTerrain" => $us->getHeuresTerrain(),
                        "nbGroupesTerrain" => 0,
                        "heuresInnovationPedagogique" => $us->getHeuresInnovationPedagogique(),
                        "nbGroupesInnovationPedagogique" => 0,
                        "validite" => $us->getValidite(),
                        "deleted" => $us->getDeleted()
                    ];
                    $this->uniteServiceAnneeService->creerUniteServiceAnnee($usa);
                }
            }
        }


    }

    public function afficherAide(){
        return IntervenantController::afficherTwig("aide.twig");
    }
}