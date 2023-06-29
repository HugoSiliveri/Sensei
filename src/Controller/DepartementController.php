<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\ConnexionUtilisateurInterface;
use App\Sensei\Lib\InfosGlobales;
use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\ComposanteServiceInterface;
use App\Sensei\Service\DepartementServiceInterface;
use App\Sensei\Service\DroitServiceInterface;
use App\Sensei\Service\EtatServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\ServiceAnnuelServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class DepartementController extends GenericController
{
    public function __construct(
        private readonly DepartementServiceInterface   $departementService,
        private readonly EtatServiceInterface          $etatService,
        private readonly ComposanteServiceInterface    $composanteService,
        private readonly ServiceAnnuelServiceInterface $serviceAnnuelService,
        private readonly DroitServiceInterface $droitService,
        private readonly ConnexionUtilisateurInterface $connexionUtilisateur
    )
    {
    }

    /**
     * @Route ("/creerDepartement", GET)
     *
     * @return Response
     */
    public function afficherFormulaireCreation(): Response
    {
        try {
            $this->droitService->verifierDroits();
            $etats = $this->etatService->recupererEtats();
            $composantes = $this->composanteService->recupererComposantes();
            return DepartementController::afficherTwig("departement/creationDepartement.twig", [
                "etats" => $etats,
                "composantes" => $composantes
            ]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return DepartementController::rediriger("accueil");
    }

    /**
     * @Route ("/creerDepartement", POST)
     *
     * @return Response
     */
    public function creerDepuisFormulaire(): Response
    {
        try {
            $libDepartement = $_POST["libDepartement"];
            $codeLettre = $_POST["codeLettre"];
            $reportMax = $_POST["reportMax"];
            $idComposante = $_POST["idComposante"];
            $idEtat = $_POST["idEtat"];

            $departement = [
                "libDepartement" => strcmp($libDepartement, "") == 0 ? null : $libDepartement,
                "codeLettre" => strcmp($codeLettre, "") == 0 ? null : $codeLettre,
                "reportMax" => $reportMax,
                "idComposante" => $idComposante,
                "idEtat" => $idEtat
            ];

            $this->departementService->creerDepartement($departement);

            MessageFlash::ajouter("success", "Le departement a bien été créé !");
        } catch (ServiceException $exception){
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return DepartementController::rediriger("accueil");
    }

    /**
     * @Route ("/departements", GET)
     *
     * @return Response
     */
    public function afficherListe(): Response
    {
        try {
            $this->droitService->verifierDroits();
            $departements = $this->departementService->recupererDepartements();
            return DepartementController::afficherTwig("departement/listeDepartements.twig", ["departements" => $departements]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return DepartementController::rediriger("accueil");

    }

    /**
     * @Route ("/supprimerDepartement/{idDepartement}", GET)
     *
     * @param int $idDepartement
     * @return Response
     */
    public function supprimer(int $idDepartement): Response
    {
        try {
            $this->droitService->verifierDroits();
            $this->departementService->supprimerDepartement($idDepartement);
            MessageFlash::ajouter("success", "Le departement a bien été supprimé !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return DepartementController::rediriger("accueil");
    }

    /**
     * @Route ("/mettreAJourDepartement/{idDepartement}", GET)
     *
     * @param int $idDepartement
     * @return Response
     */
    public function afficherFormulaireMiseAJour(int $idDepartement): Response
    {
        try {
            $this->droitService->verifierDroits();
            $departement = $this->departementService->recupererParIdentifiant($idDepartement);
            $composantes = $this->composanteService->recupererComposantes();
            $etats = $this->etatService->recupererEtats();
            return DepartementController::afficherTwig("departement/mettreAJour.twig", [
                "departement" => $departement,
                "composantes" => $composantes,
                "etats" => $etats
            ]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return DepartementController::rediriger("accueil");
        }
    }

    /**
     * @Route ("/mettreAJourDepartement/{idDepartement}", POST)
     *
     * @return Response
     */
    public function mettreAJour(): Response
    {
        try {
            $departement = [
                "idDepartement" => $_POST["idDepartement"],
                "libDepartement" => strcmp($_POST["libDepartement"], "") == 0 ? null : $_POST["libDepartement"],
                "codeLettre" => strcmp($_POST["codeLettre"], "") == 0 ? null : $_POST["codeLettre"],
                "reportMax" => $_POST["reportMax"],
                "idComposante" => $_POST["idComposante"],
                "idEtat" => $_POST["idEtat"]
            ];
            $this->departementService->modifierDepartement($departement);
            MessageFlash::ajouter("success", "Le departement a bien été modifié !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return DepartementController::rediriger("accueil");
    }

    /**
     * @Route ("/gererEtat", GET)
     *
     * @return Response
     */
    public function afficherFormulaireGestionEtat(): Response
    {
        try {
            $this->droitService->verifierDroits();
            $etats = $this->etatService->recupererEtats();
            $serviceAnnuel = $this->serviceAnnuelService->recupererParIntervenantAnnuelPlusRecent($this->connexionUtilisateur->getIdUtilisateurConnecte());
            $idDepartement = $this->departementService->recupererParLibelle(InfosGlobales::lireDepartement())->getIdDepartement() ?? $serviceAnnuel->getIdDepartement();

            $this->departementService->verifierDroitsPourGestion($this->connexionUtilisateur->getIdUtilisateurConnecte(), $idDepartement);

            return EtatController::afficherTwig("departement/gererEtat.twig", [
                "etats" => $etats,
                "idDepartement" => $idDepartement]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return EtatController::rediriger("accueil");
        }
    }

    /**
     * @Route ("/gererEtat", POST)
     *
     * @return Response
     */
    public function gererEtat(): Response
    {
        try {
            $idEtat = $_POST["idEtat"];
            $idDepartement = $_POST["idDepartement"];
            $this->departementService->changerEtat($idDepartement, $idEtat);
            MessageFlash::ajouter("success", "L'état du département a bien été changé !");
        } catch (ServiceException $exception){
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return EtatController::rediriger("accueil");
    }
}