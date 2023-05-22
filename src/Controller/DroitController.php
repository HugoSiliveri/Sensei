<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\DroitServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use Symfony\Component\HttpFoundation\Response;

class DroitController extends GenericController
{

    public function __construct(
        private readonly DroitServiceInterface $droitService
    )
    {
    }

    public function afficherFormulaireCreation(): Response
    {
        return DroitController::afficherTwig("droit/creationDroit.twig");
    }

    public function creerDepuisFormulaire(): Response
    {
        $typeDroit = $_POST["typeDroit"];

        $droit = [
            "typeDroit" => strcmp($typeDroit, "") == 0 ? null : $typeDroit
        ];

        $this->droitService->creerDroit($droit);

        MessageFlash::ajouter("success", "Le droit a bien été créé !");
        return DroitController::rediriger("accueil");
    }

    public function afficherListe(): Response
    {
        $droits = $this->droitService->recupererDroits();
        return DroitController::afficherTwig("droit/listeDroits.twig", ["droits" => $droits]);
    }

    public function supprimer(int $idDroit): Response
    {
        try {
            $this->droitService->supprimerDroit($idDroit);
            MessageFlash::ajouter("success", "Le droit a bien été supprimé !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return DroitController::rediriger("accueil");
    }

    public function afficherFormulaireMiseAJour(int $idDroit): Response
    {
        try {
            $droit = $this->droitService->recupererParIdentifiant($idDroit);
            return DroitController::afficherTwig("droit/mettreAJour.twig", ["droit" => $droit]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return DroitController::rediriger("accueil");
        }
    }

    public function mettreAJour(): Response
    {
        try {
            $droit = [
                "idDroit" => $_POST["idDroit"],
                "typeDroit" => strcmp($_POST["typeDroit"], "") == 0 ? null : $_POST["typeDroit"]
            ];
            $this->droitService->modifierDroit($droit);
            MessageFlash::ajouter("success", "Le droit a bien été modifié !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return DroitController::rediriger("accueil");
    }
}