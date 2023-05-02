<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\ConnexionUtilisateurInterface;
use App\Sensei\Service\DepartementServiceInterface;
use App\Sensei\Service\DroitServiceInterface;
use App\Sensei\Service\EmploiServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\IntervenantServiceInterface;
use App\Sensei\Service\ServiceAnnuelServiceInterface;
use App\Sensei\Service\StatutServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class IntervenantController extends GenericController
{

    public function __construct(
        private readonly IntervenantServiceInterface $intervenantService,
        private readonly StatutServiceInterface $statutService,
        private readonly DroitServiceInterface $droitService,
        private readonly ServiceAnnuelServiceInterface $serviceAnnuelService,
        private readonly EmploiServiceInterface $emploiService,
        private readonly DepartementServiceInterface $departementService,
        private readonly ConnexionUtilisateurInterface $connexionUtilisateur
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
    public function afficherDetail(): Response{
        try{
            $idIntervenant = 3637;
            $intervenant = $this->intervenantService->recupererParIdentifiant($idIntervenant);
            $statut = $this->statutService->recupererParIdentifiant($intervenant->getIdStatut());
            $droit = $this->droitService->recupererParIdentifiant($intervenant->getIdDroit());

            $servicesAnnuels = $this->serviceAnnuelService->recupererParIntervenant($idIntervenant);
            $emplois = [];
            $departements = [];
            foreach ($servicesAnnuels as $serviceAnnuel){
                $emplois[] = $this->emploiService->recupererParIdentifiant($serviceAnnuel->getIdEmploi());
                $departements[] = $this->departementService->recupererParIdentifiant($serviceAnnuel->getIdDepartement());
            }

            $parametres = [
                "intervenant" => $intervenant,
                "statut" => $statut,
                "droit" => $droit,
                "servicesAnnuels" => $servicesAnnuels,
                "emplois" => $emplois,
                "departements" => $departements];

            return GenericController::afficherTwig("intervenant/detailIntervenant.twig", $parametres);
        } catch (ServiceException $exception){
            if (strcmp($exception->getCode(), "danger") == 0) {
                //MessageFlash::ajouter("danger", $e->getMessage());
            } else {
                //MessageFlash::ajouter("warning", $e->getMessage());
            }
            return IntervenantController::rediriger("afficherListeIntervenants");
        }
    }

}