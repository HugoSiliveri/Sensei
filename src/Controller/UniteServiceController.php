<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\IntervenantServiceInterface;
use App\Sensei\Service\InterventionServiceInterface;
use App\Sensei\Service\UniteServiceAnneeServiceInterface;
use App\Sensei\Service\UniteServiceServiceInterface;
use App\Sensei\Service\VoeuServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use TypeError;

class UniteServiceController extends GenericController
{
    public function __construct(
        private readonly UniteServiceServiceInterface      $uniteServiceService,
        private readonly UniteServiceAnneeServiceInterface $uniteServiceAnneeService,
        private readonly VoeuServiceInterface              $voeuService,
        private readonly IntervenantServiceInterface       $intervenantService,
        private readonly InterventionServiceInterface      $interventionService,
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
        $unitesServices = $this->uniteServiceService->recupererUnitesServices();
        return UniteServiceController::afficherTwig("uniteService/listeUnitesServices.twig", ["unitesServices" => $unitesServices]);
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

            $voeux = [];
            $intervenants = [];
            $interventions = [];
            $i = 0;
            foreach ($unitesServicesAnnees as $uniteServiceAnnee) {
                $intervenantsParAnnee = [];
                $interventionsParAnnee = [];
                $voeuxParAnnee = $this->voeuService->recupererParIdUSA($uniteServiceAnnee->getIdUniteServiceAnnee());

                foreach ($voeuxParAnnee as $voeuxAnnuel) {
                    $intervenantsParAnnee[] = $this->intervenantService->recupererParIdentifiant($voeuxAnnuel->getIdIntervenant());
                    $interventionsParAnnee[] = $this->interventionService->recupererParIdentifiant($voeuxAnnuel->getIdIntervention());
                }

                $voeux[] = $voeuxParAnnee;
                $intervenants[] = array_unique($intervenantsParAnnee, SORT_REGULAR);
                $interventions[] = array_unique($interventionsParAnnee, SORT_REGULAR);
                $i++;
            }

            $parametres = [
                "uniteService" => $uniteService,
                "unitesServicesAnnees" => $unitesServicesAnnees,
                "voeux" => $voeux,
                "intervenants" => $intervenants,
                "interventions" => $interventions
            ];

            return UniteServiceController::afficherTwig("uniteService/detailUniteService.twig", $parametres);
        } catch (ServiceException|TypeError $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return UniteServiceController::rediriger("afficherListeUnitesServices");
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
            return UniteServiceController::rediriger("afficherListeUnitesServices");
        }
    }
}