<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\DeclarationServiceServiceInterface;
use App\Sensei\Service\DepartementServiceInterface;
use App\Sensei\Service\DroitServiceInterface;
use App\Sensei\Service\EmploiServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\IntervenantServiceInterface;
use App\Sensei\Service\ServiceAnnuelServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class ServiceAnnuelController extends GenericController
{

    public function __construct(
        private readonly ServiceAnnuelServiceInterface $serviceAnnuelService,
        private readonly DroitServiceInterface $droitService,
        private readonly DeclarationServiceServiceInterface $declarationServiceService,
        private readonly DepartementServiceInterface $departementService,
        private readonly IntervenantServiceInterface $intervenantService,
        private readonly EmploiServiceInterface $emploiService
    )
    {
    }

    public function afficherFormulaireMiseAJour(int $idServiceAnnuel): Response
    {
        try {
            $this->droitService->verifierDroits();
            $serviceAnnuel = $this->serviceAnnuelService->recupererParIdentifiant($idServiceAnnuel);
            $departement = $this->departementService->recupererParIdentifiant($serviceAnnuel->getIdDepartement());
            $intervenant = $this->intervenantService->recupererParIdentifiant($serviceAnnuel->getIdIntervenant());
            $emplois = $this->emploiService->recupererEmplois();
            return IntervenantController::afficherTwig("serviceAnnuel/mettreAJour.twig", [
                "serviceAnnuel" => $serviceAnnuel,
                "intervenant" => $intervenant,
                "emplois" => $emplois,
                "departement" => $departement]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return ServiceAnnuelController::rediriger("afficherListeIntervenants");
        }
    }

    public function mettreAJour(): Response
    {
        try {

            $serviceAnnuel = $this->serviceAnnuelService->recupererParIdentifiant($_POST["idServiceAnnuel"]);

            $serviceAnnuelTab = [
                "idServiceAnnuel" => $_POST["idServiceAnnuel"],
                "idDepartement" => $_POST["idDepartement"],
                "idIntervenant" => $_POST["idIntervenant"],
                "millesime" => $_POST["millesime"],
                "idEmploi" => $_POST["idEmploi"],
                "serviceStatuaire" => $_POST["serviceStatuaire"],
                "serviceFait" => $serviceAnnuel->getServiceFait(),
                "delta" => $serviceAnnuel->getDelta(),
                "deleted" => 0
            ];
            $this->serviceAnnuelService->modifierServiceAnnuel($serviceAnnuelTab);
            MessageFlash::ajouter("success", "Le service annuel a bien été modifié !");
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