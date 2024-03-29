<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\DroitServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\NatureServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class NatureController extends GenericController
{

    public function __construct(
        private readonly NatureServiceInterface $natureService,
        private readonly DroitServiceInterface  $droitService
    )
    {
    }

    /**
     * @Route ("/creerNature", GET)
     *
     * @return Response
     */
    public function afficherFormulaireCreation(): Response
    {
        try {
            $this->droitService->verifierDroits();
            return NatureController::afficherTwig("nature/creationNature.twig");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return NatureController::rediriger("accueil");

    }

    /**
     * @Route ("/creerNature", POST)
     *
     * @return Response
     */
    public function creerDepuisFormulaire(): Response
    {
        try {
            $libNature = $_POST["libNature"];

            $nature = [
                "libNature" => strcmp($libNature, "") == 0 ? null : $libNature
            ];
            $this->natureService->creerNature($nature);
            MessageFlash::ajouter("success", "La nature a bien été créée !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return NatureController::rediriger("accueil");
    }

    /**
     * @Route ("/natures", GET)
     *
     * @return Response
     */
    public function afficherListe(): Response
    {
        try {
            $this->droitService->verifierDroits();
            $natures = $this->natureService->recupererNatures();
            return NatureController::afficherTwig("nature/listeNatures.twig", ["natures" => $natures]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return NatureController::rediriger("accueil");
    }

    /**
     * @Route ("/supprimerNature/{idNature}", GET)
     *
     * @param int $idNature
     * @return Response
     */
    public function supprimer(int $idNature): Response
    {
        try {
            $this->droitService->verifierDroits();
            $this->natureService->supprimerNature($idNature);
            MessageFlash::ajouter("success", "La nature a bien été supprimée !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return NatureController::rediriger("accueil");
    }

    /**
     * @Route ("/mettreAJourNature/{idNature}", GET)
     *
     * @param int $idNature
     * @return Response
     */
    public function afficherFormulaireMiseAJour(int $idNature): Response
    {
        try {
            $this->droitService->verifierDroits();
            $nature = $this->natureService->recupererParIdentifiant($idNature);
            return NatureController::afficherTwig("nature/mettreAJour.twig", ["nature" => $nature]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return NatureController::rediriger("accueil");
        }
    }

    /**
     * @Route ("/mettreAJourNature/{idNature}", GET)
     *
     * @return Response
     */
    public function mettreAJour(): Response
    {
        try {
            $nature = [
                "idNature" => $_POST["idNature"],
                "libNature" => strcmp($_POST["libNature"], "") == 0 ? null : $_POST["libNature"]
            ];
            $this->natureService->modifierNature($nature);
            MessageFlash::ajouter("success", "La nature a bien été modifiée !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return NatureController::rediriger("accueil");
    }
}