<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\StatutServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class StatutController extends GenericController
{
    public function __construct(
        private readonly StatutServiceInterface $statutService
    )
    {
    }

    public function afficherFormulaireCreation(): Response
    {
        return StatutController::afficherTwig("statut/creationStatut.twig");
    }

    public function creerDepuisFormulaire(): Response
    {
        $libStatut = $_POST["libStatut"];
        $nbHeures = $_POST["nbHeures"];

        $statut = [
            "libStatut" => strcmp($libStatut, "") == 0 ? null : $libStatut,
            "nbHeures" => $nbHeures == 0 ? null : $nbHeures
        ];

        $this->statutService->creerStatut($statut);

        MessageFlash::ajouter("success", "Le statut a bien été créé !");
        return StatutController::rediriger("accueil");
    }

    public function afficherListe(): Response
    {
        $statuts = $this->statutService->recupererStatuts();
        return StatutController::afficherTwig("statut/listeStatuts.twig", ["statuts" => $statuts]);
    }

    public function supprimer(int $idStatut): Response
    {
        try {
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

    public function afficherFormulaireMiseAJour(int $idStatut): Response
    {
        try {
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