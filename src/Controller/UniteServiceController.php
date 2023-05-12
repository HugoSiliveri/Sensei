<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Model\DataObject\UniteService;
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

    public function afficherFormulaireCreation(): Response {
        return UniteServiceController::afficherTwig("uniteService/creationUniteService.twig");
    }

    public function creerDepuisFormulaire(): Response {
        try {
            $ancetre = $_POST["ancetre"];

            if ($ancetre != ""){
                $idAncetre = $this->uniteServiceService->rechercherUniteService($ancetre)->getIdUniteService();
            } else {
                $idAncetre = null;
            }
            $idUSReferentiel = $_POST["idUSReferentiel"];
            $libUS = $_POST["libUS"];
            $nature =  $_POST["nature"];
            $anneeOuverture = $_POST["anneeOuverture"];
            $anneeCloture = $_POST["anneeCloture"];
            $ECTS = $_POST["ECTS"];
            $payeur = $_POST["payeur"];
            $validite = $_POST["validite"];


            $uniteService = [
                "idUSReferentiel" => strcmp($idUSReferentiel, "") == 0 ? null : $idUSReferentiel,
                "libUS" =>  strcmp($libUS, "") == 0 ? null : $libUS,
                "nature" =>  strcmp($nature, "") == 0 ? null : $nature,
                "ancetre" => $idAncetre,
                "anneeOuverture" =>  strcmp($anneeOuverture, "") == 0 ? null : $anneeOuverture,
                "anneeCloture" =>  strcmp($anneeCloture, "") == 0 ? null : $anneeCloture,
                "ECTS" =>  strcmp($ECTS, "") == 0 ? null : $ECTS,
                "heuresCM" => $_POST["heuresCM"],
                "heuresTD" => $_POST["heuresTD"],
                "heuresTP" => $_POST["heuresTP"],
                "heuresStage" => $_POST["heuresStage"],
                "heuresTerrain" => $_POST["heuresTerrain"],
                "semestre" => $_POST["semestre"],
                "saison" => $_POST["saison"],
                "payeur" =>  strcmp($payeur, "") == 0 ? null : $payeur,
                "validite" =>  strcmp($validite, "") == 0 ? null : $validite,
                "deleted" => 0,
            ];

            $this->uniteServiceService->creerUniteService($uniteService);

            MessageFlash::ajouter("success", "L'unité de service a bien été créé !");
            return UniteServiceController::rediriger("accueil");

        } catch (ServiceException $exception){
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
            return UniteServiceController::rediriger("afficherListeUnitesServices");
        }
    }
}