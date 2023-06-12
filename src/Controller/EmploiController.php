<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\DroitServiceInterface;
use App\Sensei\Service\EmploiServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use Symfony\Component\HttpFoundation\Response;

class EmploiController extends GenericController
{
    public function __construct(
        private readonly EmploiServiceInterface $emploiService,
        private readonly DroitServiceInterface $droitService
    )
    {
    }

    public function afficherFormulaireCreation(): Response
    {
        try {
            $this->droitService->verifierDroits();
            return EmploiController::afficherTwig("emploi/creationEmploi.twig");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return DroitController::rediriger("accueil");

    }

    public function creerDepuisFormulaire(): Response
    {
        $libEmploi = $_POST["libEmploi"];

        $emploi = [
            "libEmploi" => strcmp($libEmploi, "") == 0 ? null : $libEmploi
        ];

        $this->emploiService->creerEmploi($emploi);

        MessageFlash::ajouter("success", "L'emploi a bien été créé !");
        return EmploiController::rediriger("accueil");
    }

    public function afficherListe(): Response
    {
        try {
            $this->droitService->verifierDroits();
            $emplois = $this->emploiService->recupererEmplois();
            return EmploiController::afficherTwig("emploi/listeEmplois.twig", ["emplois" => $emplois]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return DroitController::rediriger("accueil");

    }

    public function supprimer(int $idEmploi): Response
    {
        try {
            $this->droitService->verifierDroits();
            $this->emploiService->supprimerEmploi($idEmploi);
            MessageFlash::ajouter("success", "L'emploi a bien été supprimé !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return EmploiController::rediriger("accueil");
    }

    public function afficherFormulaireMiseAJour(int $idEmploi): Response
    {
        try {
            $this->droitService->verifierDroits();
            $emploi = $this->emploiService->recupererParIdentifiant($idEmploi);
            return EmploiController::afficherTwig("emploi/mettreAJour.twig", ["emploi" => $emploi]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return EmploiController::rediriger("accueil");
        }
    }

    public function mettreAJour(): Response
    {
        try {
            $emploi = [
                "idEmploi" => $_POST["idEmploi"],
                "libEmploi" => strcmp($_POST["libEmploi"], "") == 0 ? null : $_POST["libEmploi"]
            ];
            $this->emploiService->modifierEmploi($emploi);
            MessageFlash::ajouter("success", "L'emploi a bien été modifié !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return EmploiController::rediriger("accueil");
    }
}