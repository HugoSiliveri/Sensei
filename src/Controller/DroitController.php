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

    /**
     * @Route ("/creerDroit", GET)
     *
     * @return Response
     */
    public function afficherFormulaireCreation(): Response
    {
        try {
            $this->droitService->verifierDroits();
            return DroitController::afficherTwig("droit/creationDroit.twig");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return DroitController::rediriger("accueil");
    }

    /**
     * @Route ("/creerDroit", POST)
     *
     * @return Response
     */
    public function creerDepuisFormulaire(): Response
    {
        try {
            $typeDroit = $_POST["typeDroit"];

            $droit = [
                "typeDroit" => strcmp($typeDroit, "") == 0 ? null : $typeDroit
            ];
            $this->droitService->creerDroit($droit);
            MessageFlash::ajouter("success", "Le droit a bien été créé !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return DroitController::rediriger("accueil");
    }

    /**
     * @Route ("/droits", GET)
     *
     * @return Response
     */
    public function afficherListe(): Response
    {
        try {
            $this->droitService->verifierDroits();
            $droits = $this->droitService->recupererDroits();
            return DroitController::afficherTwig("droit/listeDroits.twig", ["droits" => $droits]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return DroitController::rediriger("accueil");

    }

    /**
     * @Route ("/supprimerDroit/{idDroit}", GET)
     *
     * @param int $idDroit
     * @return Response
     */
    public function supprimer(int $idDroit): Response
    {
        try {
            $this->droitService->verifierDroits();
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

    /**
     * @Route ("/mettreAJourDroit/{idDroit}", GET)
     *
     * @param int $idDroit
     * @return Response
     */
    public function afficherFormulaireMiseAJour(int $idDroit): Response
    {
        try {
            $this->droitService->verifierDroits();
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

    /**
     * @Route ("/mettreAJourDroit/{idDroit}", POST)
     *
     * @return Response
     */
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