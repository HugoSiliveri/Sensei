<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\ConnexionUtilisateurInterface;
use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\DeclarationServiceServiceInterface;
use App\Sensei\Service\DepartementServiceInterface;
use App\Sensei\Service\DroitServiceInterface;
use App\Sensei\Service\EmploiServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\IntervenantServiceInterface;
use App\Sensei\Service\InterventionServiceInterface;
use App\Sensei\Service\ResponsableUSServiceInterface;
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
        private readonly InterventionServiceInterface       $interventionService,
        private readonly VoeuServiceInterface               $voeuService,
        private readonly ResponsableUSServiceInterface      $responsableUSService,
        private readonly DeclarationServiceServiceInterface $declarationServiceService,
        private readonly ConnexionUtilisateurInterface      $connexionUtilisateur
    )
    {
    }

    /**
     * Méthode qui affiche la liste des utilisateurs.
     *
     * @return Response
     */
    public function afficherListe(): Response
    {
        $intervenants = $this->intervenantService->recupererIntervenants();
        return IntervenantController::afficherTwig("intervenant/listeIntervenants.twig", ["intervenants" => $intervenants]);
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
            foreach ($servicesAnnuels as $serviceAnnuel) {
                $emplois[] = $this->emploiService->recupererParIdentifiant($serviceAnnuel->getIdEmploi());
                $departements[] = $this->departementService->recupererParIdentifiant($serviceAnnuel->getIdDepartement());
            }

            //$voeux = $this->voeuService->recupererVueParIntervenant($idIntervenant);
            $declarationsServices = $this->declarationServiceService->recupererVueParIdIntervenant($idIntervenant);

            $parametres = [
                "intervenant" => $intervenant,
                "statut" => $statut,
                "droit" => $droit,
                "servicesAnnuels" => $servicesAnnuels,
                "emplois" => $emplois,
                "departements" => $departements,
                "declarationsServices" => $declarationsServices
            ];

            return IntervenantController::afficherTwig("intervenant/detailIntervenant.twig", $parametres);
        } catch (ServiceException|TypeError $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("afficherFormulaireCreationIntervenant");
        }
    }

    public function afficherFormulaireCreation(): Response
    {
        return IntervenantController::afficherTwig("intervenant/creationIntervenant.twig");
    }

    public function creerDepuisFormulaire(): Response
    {

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

        MessageFlash::ajouter("success", "L'intervenant a bien été créé !");
        return IntervenantController::rediriger("accueil");
    }

    public function afficherAccueil(): Response
    {
        try {
            $uid = 3637; // TODO : A changer
            $anneeEnCours = 2023;
            $utilisateur = $this->intervenantService->recupererParIdentifiant($uid);
            $responsabilitesAnnuel = $this->responsableUSService->recupererParIdIntervenantAnnuel($uid, $anneeEnCours);

            $us = [];
            $usa = [];
            $i = 0;
            foreach ($responsabilitesAnnuel as $responsabilite) {
                $usa[] = $this->uniteServiceAnneeService->recupererParIdentifiant($responsabilite->getIdUniteServiceAnnee());
                $us[] = $this->uniteServiceService->recupererParIdentifiant($usa[$i]->getIdUniteService());
                $i++;
            }

            $parametres = [
                "utilisateur" => $utilisateur,
                "responsabilites" => $responsabilitesAnnuel,
                "unitesServicesAnnees" => $usa,
                "unitesServices" => $us
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
        $departements = $this->departementService->recupererDepartements();
        return IntervenantController::afficherTwig("gestion.twig", [
            "departements" => $departements
        ]);
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
            $intervenant = $this->intervenantService->recupererParIdentifiant($idIntervenant);
            return IntervenantController::afficherTwig("intervenant/mettreAJour.twig", ["intervenant" => $intervenant]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("accueil");
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
}