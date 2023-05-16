<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\ComposanteServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use Symfony\Component\HttpFoundation\Response;

class ComposanteController extends GenericController
{
    public function __construct(
        private readonly ComposanteServiceInterface $composanteService
    )
    {
    }

    public function afficherFormulaireCreation(): Response{
        return ComposanteController::afficherTwig("composante/creationComposante.twig");
    }

    public function creerDepuisFormulaire(): Response {
        $libComposante = $_POST["libComposante"];
        $anneeDeTravail = $_POST["anneeDeTravail"];
        $anneeDeValidation = $_POST["anneeDeValidation"];

        $composante = [
            "libComposante" => strcmp($libComposante, "") == 0 ? null : $libComposante,
            "anneeDeTravail" => $anneeDeTravail,
            "anneeDeValidation" => $anneeDeValidation
        ];

        $this->composanteService->creerComposante($composante);

        MessageFlash::ajouter("success", "La composante a bien été créée !");
        return ComposanteController::rediriger("gestion");
    }

    public function afficherListe(): Response{
        $composantes = $this->composanteService->recupererComposantes();
        return ComposanteController::afficherTwig("composante/listeComposantes.twig", ["composantes" => $composantes]);
    }

    public function supprimer(int $idComposante): Response {
        try {
            $this->composanteService->supprimerComposante($idComposante);
            MessageFlash::ajouter("success", "La composante a bien été supprimée !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return ComposanteController::rediriger("afficherListeComposantes");
    }

    public function afficherFormulaireMiseAJour(int $idComposante): Response{
        try {
            $composante = $this->composanteService->recupererParIdentifiant($idComposante);
            return ComposanteController::afficherTwig("composante/mettreAJour.twig", ["composante" => $composante]);
        } catch (ServiceException $exception){
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return ComposanteController::rediriger("afficherListeComposantes");
        }
    }

    public function mettreAJour(): Response {
        try {
            $composante = [
                "idComposante" => $_POST["idComposante"],
                "libComposante" => strcmp($_POST["libComposante"], "") == 0 ? null : $_POST["libComposante"],
                "anneeDeTravail" => $_POST["anneeDeTravail"],
                "anneeDeValidation" => $_POST["anneeDeValidation"]
            ];
            $this->composanteService->modifierComposante($composante);
            MessageFlash::ajouter("success", "La composante a bien été modifiée !");
        } catch (ServiceException $exception){
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return ComposanteController::rediriger("afficherListeComposantes");
    }
}