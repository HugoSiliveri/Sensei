<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\AppartenirServiceInterface;
use App\Sensei\Service\ColorationServiceInterface;
use App\Sensei\Service\DeclarationServiceServiceInterface;
use App\Sensei\Service\DepartementServiceInterface;
use App\Sensei\Service\DroitServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\IntervenantServiceInterface;
use App\Sensei\Service\InterventionServiceInterface;
use App\Sensei\Service\NatureServiceInterface;
use App\Sensei\Service\PayeurServiceInterface;
use App\Sensei\Service\SaisonServiceInterface;
use App\Sensei\Service\SemestreServiceInterface;
use App\Sensei\Service\UniteServiceAnneeServiceInterface;
use App\Sensei\Service\UniteServiceServiceInterface;
use App\Sensei\Service\VoeuServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use TypeError;

class UniteServiceController extends GenericController
{
    public function __construct(
        private readonly UniteServiceServiceInterface       $uniteServiceService,
        private readonly UniteServiceAnneeServiceInterface  $uniteServiceAnneeService,
        private readonly IntervenantServiceInterface        $intervenantService,
        private readonly InterventionServiceInterface       $interventionService,
        private readonly PayeurServiceInterface             $payeurService,
        private readonly DepartementServiceInterface        $departementService,
        private readonly AppartenirServiceInterface         $appartenirService,
        private readonly NatureServiceInterface             $natureService,
        private readonly ColorationServiceInterface         $colorationService,
        private readonly DeclarationServiceServiceInterface $declarationServiceService,
        private readonly DroitServiceInterface $droitService,
        private readonly SaisonServiceInterface $saisonService,
        private readonly SemestreServiceInterface $semestreService
    )
    {
    }

    /**
     * Méthode qui affiche le détail d'un intervenant.
     *
     * @param int $idUniteService
     * @return Response
     */
    public function afficherDetail(int $idUniteService): Response
    {
        try {
            $uniteService = $this->uniteServiceService->recupererParIdentifiant($idUniteService);
            $unitesServicesAnnees = $this->uniteServiceAnneeService->recupererParUniteService($idUniteService);
            $payeur = $this->payeurService->recupererParIdentifiant($uniteService->getIdPayeur());
            $appartenirTab = $this->appartenirService->recupererParIdUniteService($uniteService->getIdUniteService());
            $nature = $this->natureService->recupererParIdentifiant($uniteService->getNature());
            $saison = $this->saisonService->recupererParIdentifiant($uniteService->getSaison());
            $semestre = $this->semestreService->recupererParIdentifiant($uniteService->getSemestre());

            $departements = [];
            foreach ($appartenirTab as $appartenir) {
                $departements[] = $this->departementService->recupererParIdentifiant($appartenir->getIdDepartement());
            }

            $declarationsServices = [];
            $intervenants = [];
            $interventions = [];
            $colorations = [];
            $colorationsParAnnee = [];
            $i = 0;
            $j = 0;
            foreach ($unitesServicesAnnees as $uniteServiceAnnee) {
                $intervenantsParAnnee = [];
                $interventionsParAnnee = [];

                $departementsColoration = [];
                $colorationsParAnnee[] = $this->colorationService->recupererParIdUniteServiceAnnee($uniteServiceAnnee->getIdUniteServiceAnnee());

                foreach ($colorationsParAnnee[$j] as $coloration) {
                    $departementsColoration[] = $this->departementService->recupererParIdentifiant($coloration->getIdDepartement());
                }
                $declarationsParAnnee = $this->declarationServiceService->recupererParIdUSA($uniteServiceAnnee->getIdUniteServiceAnnee());

                foreach ($declarationsParAnnee as $declarationsServicesAnnuel) {
                    $intervenantsParAnnee[] = $this->intervenantService->recupererParIdentifiant($declarationsServicesAnnuel->getIdIntervenant());
                    $interventionsParAnnee[] = $this->interventionService->recupererParIdentifiant($declarationsServicesAnnuel->getIdIntervention());
                }

                $declarationsServices[] = $declarationsParAnnee;
                $intervenants[] = array_unique($intervenantsParAnnee, SORT_REGULAR);
                $interventions[] = array_unique($interventionsParAnnee, SORT_REGULAR);
                $colorations[] = array_unique($departementsColoration, SORT_REGULAR);
                $i++;
                $j++;
            }

            $parametres = [
                "uniteService" => $uniteService,
                "unitesServicesAnnees" => $unitesServicesAnnees,
                "declarationsServices" => $declarationsServices,
                "intervenants" => $intervenants,
                "interventions" => $interventions,
                "payeur" => $payeur,
                "departements" => $departements,
                "nature" => $nature,
                "colorations" => $colorations,
                "saison" => $saison,
                "semestre" => $semestre
            ];

            return UniteServiceController::afficherTwig("uniteService/detailUniteService.twig", $parametres);
        } catch (ServiceException|TypeError $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return UniteServiceController::rediriger("afficherListeUnitesServicesAnnee");
        }
    }

    public function afficherFormulaireCreation(): Response
    {
        try {
            $this->droitService->verifierDroits();
            $payeurs = $this->payeurService->recupererPayeurs();
            $departements = $this->departementService->recupererDepartements();
            return UniteServiceController::afficherTwig("uniteService/creationUniteService.twig", [
                "payeurs" => $payeurs,
                "departements" => $departements]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return IntervenantController::rediriger("afficherListeUnitesServicesAnnee");

    }

    public function creerDepuisFormulaire(): Response
    {
        try {
            $ancetre = $_POST["ancetre"];

            if ($ancetre != "") {
                $idAncetre = $this->uniteServiceService->rechercherUniteService($ancetre)->getIdUniteService();
            } else {
                $idAncetre = null;
            }
            $idUSReferentiel = $_POST["idUSReferentiel"];
            $libUS = $_POST["libUS"];
            $nature = $_POST["nature"];
            $anneeOuverture = $_POST["anneeOuverture"];
            $anneeCloture = $_POST["anneeCloture"];
            $ECTS = $_POST["ECTS"];
            $validite = $_POST["validite"];

            $uniteService = [
                "idUSReferentiel" => strcmp($idUSReferentiel, "") == 0 ? null : $idUSReferentiel,
                "libUS" => strcmp($libUS, "") == 0 ? null : $libUS,
                "nature" => strcmp($nature, "") == 0 ? null : $nature,
                "ancetre" => $idAncetre,
                "anneeOuverture" => strcmp($anneeOuverture, "") == 0 ? null : $anneeOuverture,
                "anneeCloture" => strcmp($anneeCloture, "") == 0 ? null : $anneeCloture,
                "ECTS" => strcmp($ECTS, "") == 0 ? null : $ECTS,
                "heuresCM" => $_POST["heuresCM"],
                "heuresTD" => $_POST["heuresTD"],
                "heuresTP" => $_POST["heuresTP"],
                "heuresStage" => $_POST["heuresStage"],
                "heuresTerrain" => $_POST["heuresTerrain"],
                "heuresInnovationPedagogique" => $_POST["heuresInnovationPedagogique"],
                "semestre" => $_POST["semestre"],
                "saison" => $_POST["saison"],
                "idPayeur" => $_POST["idPayeur"],
                "validite" => strcmp($validite, "") == 0 ? null : $validite,
                "deleted" => 0,
            ];

            $this->uniteServiceService->creerUniteService($uniteService);

            $appartenir = [
                "idDepartement" => $this->departementService->recupererParIdentifiant($_POST["appartenir"])->getIdDepartement(),
                "idUniteService" => $this->uniteServiceService->recupererDernierUniteService()->getIdUniteService()
            ];
            $this->appartenirService->creerAppartenir($appartenir);

            MessageFlash::ajouter("success", "L'unité de service a bien été créé !");

            if (isset($_POST["checkbox"])) {
                $uniteService = $this->uniteServiceService->recupererDernierUniteService();

                $uniteServiceAnnee = [
                    "idDepartement" => $_POST["idDepartement"],
                    "idUniteService" => $uniteService->getIdUniteService(),
                    "libUSA" => strcmp($libUS, "") == 0 ? null : $libUS,
                    "millesime" => $_POST["millesime"],
                    "heuresCM" => $_POST["heuresCM"],
                    "nbGroupesCM" => $_POST["nbGroupesCM"],
                    "heuresTD" => $_POST["heuresTD"],
                    "nbGroupesTD" => $_POST["nbGroupesTD"],
                    "heuresTP" => $_POST["heuresTP"],
                    "nbGroupesTP" => $_POST["nbGroupesTP"],
                    "heuresStage" => $_POST["heuresStage"],
                    "nbGroupesStage" => $_POST["nbGroupesStage"],
                    "heuresTerrain" => $_POST["heuresTerrain"],
                    "nbGroupesTerrain" => $_POST["nbGroupesTerrain"],
                    "heuresInnovationPedagogique" => $_POST["heuresInnovationPedagogique"],
                    "nbGroupesInnovationPedagogique" => $_POST["nbGroupesInnovationPedagogique"],
                    "validite" => strcmp($validite, "") == 0 ? null : $validite,
                    "deleted" => 0
                ];

                $this->uniteServiceAnneeService->creerUniteServiceAnnee($uniteServiceAnnee);
                MessageFlash::ajouter("success", "L'unité de service pour l'année " . $_POST["millesime"] . " a bien été créé !");
            }

            return UniteServiceController::rediriger("accueil");

        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return UniteServiceController::rediriger("afficherFormulaireCreationUniteService");
        }
    }

    public function chercherUniteService(): Response
    {
        try {
            $recherche = $_POST["uniteService"];
            $uniteService = $this->uniteServiceService->rechercherUniteService($recherche);
            return UniteServiceController::rediriger("afficherDetailUniteService", ["idUniteService" => $uniteService->getIdUniteService()]);
        } catch (ServiceException|TypeError $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return UniteServiceController::rediriger("afficherListeUnitesServicesAnnee");
        }
    }

    public function afficherFormulaireMiseAJour(int $idUniteService): Response
    {
        try {
            $this->droitService->verifierDroits();
            $uniteService = $this->uniteServiceService->recupererParIdentifiant($idUniteService);
            $payeurs = $this->payeurService->recupererPayeurs();
            $departements = $this->departementService->recupererDepartements();
            return UniteServiceController::afficherTwig("uniteService/mettreAJour.twig", [
                "uniteService" => $uniteService,
                "payeurs" => $payeurs,
                "departements" => $departements]);
        } catch (ServiceException|TypeError $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return UniteServiceController::rediriger("afficherListeUnitesServicesAnnee");
        }

    }

    public function mettreAJour(): Response
    {
        try {
            $ancetre = $_POST["ancetre"];

            if ($ancetre != "") {
                $idAncetre = $this->uniteServiceService->rechercherUniteService($ancetre)->getIdUniteService();
            } else {
                $idAncetre = null;
            }

            $idUSReferentiel = $_POST["idUSReferentiel"];
            $libUS = $_POST["libUS"];
            $nature = $_POST["nature"];
            $anneeOuverture = $_POST["anneeOuverture"];
            $anneeCloture = $_POST["anneeCloture"];
            $ECTS = $_POST["ECTS"];
            $validite = $_POST["validite"];

            $uniteService = [
                "idUniteService" => $_POST["idUniteService"],
                "idUSReferentiel" => strcmp($idUSReferentiel, "") == 0 ? null : $idUSReferentiel,
                "libUS" => strcmp($libUS, "") == 0 ? null : $libUS,
                "nature" => strcmp($nature, "") == 0 ? null : $nature,
                "ancetre" => $idAncetre,
                "anneeOuverture" => strcmp($anneeOuverture, "") == 0 ? null : $anneeOuverture,
                "anneeCloture" => strcmp($anneeCloture, "") == 0 ? null : $anneeCloture,
                "ECTS" => strcmp($ECTS, "") == 0 ? null : $ECTS,
                "heuresCM" => $_POST["heuresCM"],
                "heuresTD" => $_POST["heuresTD"],
                "heuresTP" => $_POST["heuresTP"],
                "heuresStage" => $_POST["heuresStage"],
                "heuresTerrain" => $_POST["heuresTerrain"],
                "heuresInnovationPedagogique" => $_POST["heuresInnovationPedagogique"],
                "semestre" => $_POST["semestre"],
                "saison" => $_POST["saison"],
                "idPayeur" => $_POST["idPayeur"],
                "validite" => strcmp($validite, "") == 0 ? null : $validite,
                "deleted" => 0,
            ];
            $this->uniteServiceService->modifierUniteService($uniteService);
            MessageFlash::ajouter("success", "L'unite de service a bien été modifiée !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return IntervenantController::rediriger("accueil");
    }
}