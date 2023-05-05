<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\ConnexionUtilisateurInterface;
use App\Sensei\Lib\MessageFlash;
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

class IntervenantController extends GenericController
{

    public function __construct(
        private readonly IntervenantServiceInterface       $intervenantService,
        private readonly StatutServiceInterface            $statutService,
        private readonly DroitServiceInterface             $droitService,
        private readonly ServiceAnnuelServiceInterface     $serviceAnnuelService,
        private readonly EmploiServiceInterface            $emploiService,
        private readonly DepartementServiceInterface       $departementService,
        private readonly UniteServiceServiceInterface      $uniteServiceService,
        private readonly UniteServiceAnneeServiceInterface $uniteServiceAnneeService,
        private readonly InterventionServiceInterface      $interventionService,
        private readonly VoeuServiceInterface              $voeuService,
        private readonly ResponsableUSServiceInterface     $responsableUSService,
        private readonly ConnexionUtilisateurInterface     $connexionUtilisateur
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
        return GenericController::afficherTwig("intervenant/listeIntervenants.twig", ["intervenants" => $intervenants]);
    }

    /**
     * Méthode qui affiche le détail d'un intervenant.
     *
     * @return Response
     */
    public function afficherDetail(): Response
    {
        try {
            $idIntervenant = 3637;// TODO : A changer
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

            $voeux = $this->voeuService->recupererVueParIntervenant($idIntervenant);

            $parametres = [
                "intervenant" => $intervenant,
                "statut" => $statut,
                "droit" => $droit,
                "servicesAnnuels" => $servicesAnnuels,
                "emplois" => $emplois,
                "departements" => $departements,
                "voeux" => $voeux];

            return GenericController::afficherTwig("intervenant/detailIntervenant.twig", $parametres);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("afficherListeIntervenants");
        }
    }

    public function afficherAccueil(): Response
    {
        try {
            $uid = 3637; // TODO : A changer
            $anneeEnCours = 2023;
            $utilisateur = $this->intervenantService->recupererParIdentifiant($uid);
            $responsabilitesAnnuel = $this->responsableUSService->recupererParIdIntervenantAnnuel($uid, $anneeEnCours);

            $us = []; $usa = []; $i = 0;
            foreach ($responsabilitesAnnuel as $responsabilite){
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

            return GenericController::afficherTwig("accueil.twig", $parametres);
        } catch (ServiceException $exception){
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("accueil");
        }

    }
}