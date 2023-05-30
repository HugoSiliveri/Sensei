<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\ConnexionUtilisateurInterface;
use App\Sensei\Lib\InfosGlobales;
use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\DepartementServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\ServiceAnnuelServiceInterface;
use App\Sensei\Service\UniteServiceAnneeServiceInterface;
use App\Sensei\Service\UniteServiceServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class UniteServiceAnneeController extends GenericController
{

    public function __construct(
        private readonly UniteServiceAnneeServiceInterface $uniteServiceAnneeService,
        private readonly UniteServiceServiceInterface      $uniteServiceService,
        private readonly DepartementServiceInterface       $departementService,
        private readonly ServiceAnnuelServiceInterface     $serviceAnnuelService,
        private readonly ConnexionUtilisateurInterface     $connexionUtilisateur
    )
    {
    }

    /**
     * Méthode qui affiche la liste des unités de services pour l'année et le département de l'utilisateur (ou modifié dans
     * la page de gestion).
     *
     * @return Response
     * @throws ServiceException
     */
    public function afficherListe(): Response
    {
        try {
            $serviceAnnuel = $this->serviceAnnuelService->recupererParIntervenantAnnuelPlusRecent($this->connexionUtilisateur->getIdUtilisateurConnecte());
            $idDepartement = $this->departementService->recupererParLibelle(InfosGlobales::lireDepartement())->getIdDepartement() ?? $serviceAnnuel->getIdDepartement();
            $annee = $serviceAnnuel->getMillesime();
            $anneeActuelle = InfosGlobales::lireAnnee() ?? $annee;


            $unitesServicesAnneeDuDepartement = $this->uniteServiceAnneeService->recupererUnitesServicesPourUneAnneePourUnDepartement($anneeActuelle, $idDepartement);
            $unitesServicesDuDepartement = [];
            foreach ($unitesServicesAnneeDuDepartement as $uniteServiceAnneeDuDepartement) {
                $unitesServicesDuDepartement[] = $this->uniteServiceService->recupererParIdentifiant($uniteServiceAnneeDuDepartement->getIdUniteService());
            }

            $unitesServicesAnneeAvecColoration = $this->uniteServiceAnneeService->recupererUniteServiceAnneeUniquementColoration($anneeActuelle, $idDepartement);
            $unitesServicesAvecColoration = [];
            foreach ($unitesServicesAnneeAvecColoration as $uniteServiceAvecColoration) {
                $unitesServicesAvecColoration[] = $this->uniteServiceService->recupererParIdentifiant($uniteServiceAvecColoration->getIdUniteService());
            }
            return UniteServiceController::afficherTwig("uniteServiceAnnee/listeUnitesServicesAnnee.twig", [
                "unitesServicesAnneeDuDepartement" => $unitesServicesAnneeDuDepartement,
                "unitesServicesAnneeAvecColoration" => $unitesServicesAnneeAvecColoration,
                "unitesServicesDuDepartement" => $unitesServicesDuDepartement,
                "unitesServicesAvecColoration" => $unitesServicesAvecColoration]);

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
     * @throws ServiceException
     */
    public function afficherFormulaireMiseAJour(int $idUniteServiceAnnee): Response
    {
        $uniteServiceAnnee = $this->uniteServiceAnneeService->recupererParIdentifiant($idUniteServiceAnnee);
        $uniteService = $this->uniteServiceService->recupererParIdentifiant($uniteServiceAnnee->getIdUniteService());
        $departements = $this->departementService->recupererDepartements();
        return UniteServiceController::afficherTwig("uniteServiceAnnee/mettreAJour.twig", [
            "uniteServiceAnnee" => $uniteServiceAnnee,
            "uniteService" => $uniteService,
            "departements" => $departements]);
    }

    public function mettreAJour(): Response
    {
        try {
            $uniteServiceAnnee = [
                "idUniteServiceAnnee" => $_POST["idUniteServiceAnnee"],
                "idDepartement" => $_POST["idDepartement"],
                "idUniteService" => $_POST["idUniteService"],
                "libUSA" => strcmp($_POST["libUSA"], "") == 0 ? null : $_POST["libUSA"],
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
                "validite" => strcmp($_POST["validite"], "") == 0 ? null : $_POST["validite"],
                "deleted" => 0,
            ];

            $this->uniteServiceAnneeService->modifierUniteServiceAnnee($uniteServiceAnnee);
            MessageFlash::ajouter("success", "L'unite de service annuel a bien été modifiée !");
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