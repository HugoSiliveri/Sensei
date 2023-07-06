<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\DroitServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\StatutServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class StatutController extends GenericController
{
    public function __construct(
        private readonly StatutServiceInterface $statutService,
        private readonly DroitServiceInterface  $droitService
    )
    {
    }

    /**
     * @Route ("/creerStatut", GET)
     *
     * @return Response
     */
    public function afficherFormulaireCreation(): Response
    {
        try {
            $this->droitService->verifierDroits();
            return StatutController::afficherTwig("statut/creationStatut.twig");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return StatutController::rediriger("accueil");

    }

    /**
     * @Route ("/creerPayeur", POST)
     *
     * @return Response
     */
    public function creerDepuisFormulaire(): Response
    {
        try {
            $libStatut = $_POST["libStatut"];
            $nbHeures = $_POST["nbHeures"];

            $statut = [
                "libStatut" => strcmp($libStatut, "") == 0 ? null : $libStatut,
                "nbHeures" => $nbHeures == 0 ? null : $nbHeures
            ];
            $this->statutService->creerStatut($statut);
            MessageFlash::ajouter("success", "Le statut a bien été créé !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return StatutController::rediriger("accueil");
    }

    /**
     * @Route ("/statuts", GET)
     *
     * @return Response
     */
    public function afficherListe(): Response
    {
        try {
            $this->droitService->verifierDroits();
            $statuts = $this->statutService->recupererStatuts();
            return StatutController::afficherTwig("statut/listeStatuts.twig", ["statuts" => $statuts]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return StatutController::rediriger("accueil");

    }

    /**
     * @Route ("/supprimerStatut/{idStatut}, GET}
     *
     * @param int $idStatut
     * @return Response
     */
    public function supprimer(int $idStatut): Response
    {
        try {
            $this->droitService->verifierDroits();
            $this->statutService->supprimerStatut($idStatut);
            MessageFlash::ajouter("success", "Le statut a bien été supprimé !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return StatutController::rediriger("accueil");
    }

    /**
     * @Route ("/mettreAJourStatut/{idStatut}", GET)
     *
     * @param int $idStatut
     * @return Response
     */
    public function afficherFormulaireMiseAJour(int $idStatut): Response
    {
        try {
            $this->droitService->verifierDroits();
            $statut = $this->statutService->recupererParIdentifiant($idStatut);
            return StatutController::afficherTwig("statut/mettreAJour.twig", ["statut" => $statut]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return StatutController::rediriger("accueil");
        }
    }

    /**
     * @Route ("/mettreAJourStatut/{idStatut}", POST)
     *
     * @return Response
     */
    public function mettreAJour(): Response
    {
        try {
            $statut = [
                "idStatut" => $_POST["idStatut"],
                "libStatut" => strcmp($_POST["libStatut"], "") == 0 ? null : $_POST["libStatut"],
                "nbHeures" => $_POST["nbHeures"] == 0 ? null : $_POST["nbHeures"]
            ];
            $this->statutService->modifierStatut($statut);
            MessageFlash::ajouter("success", "Le statut a bien été modifié !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return StatutController::rediriger("accueil");
    }
}