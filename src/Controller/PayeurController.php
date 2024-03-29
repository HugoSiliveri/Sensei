<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\DroitServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\PayeurServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class PayeurController extends GenericController
{
    public function __construct(
        private readonly PayeurServiceInterface $payeurService,
        private readonly DroitServiceInterface  $droitService
    )
    {
    }

    /**
     * @Route ("/creerPayeur", GET)
     *
     * @return Response
     */
    public function afficherFormulaireCreation(): Response
    {
        try {
            $this->droitService->verifierDroits();
            return PayeurController::afficherTwig("payeur/creationPayeur.twig");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return PayeurController::rediriger("accueil");

    }

    /**
     * @Route ("/creerPayeur", POST)
     *
     * @return Response
     * @throws ServiceException
     */
    public function creerDepuisFormulaire(): Response
    {
        $libPayeur = $_POST["libPayeur"];

        $payeur = [
            "libPayeur" => strcmp($libPayeur, "") == 0 ? null : $libPayeur
        ];

        $this->payeurService->creerPayeur($payeur);

        MessageFlash::ajouter("success", "Le payeur a bien été créé !");
        return PayeurController::rediriger("accueil");
    }

    /**
     * @Route ("/payeurs", GET)
     *
     * @return Response
     */
    public function afficherListe(): Response
    {
        try {
            $this->droitService->verifierDroits();
            $payeurs = $this->payeurService->recupererPayeurs();
            return PayeurController::afficherTwig("payeur/listePayeurs.twig", ["payeurs" => $payeurs]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return PayeurController::rediriger("accueil");

    }

    /**
     * @Route ("/supprimerPayeur/{idPayeur}", GET)
     *
     * @param int $idPayeur
     * @return Response
     */
    public function supprimer(int $idPayeur): Response
    {
        try {
            $this->droitService->verifierDroits();
            $this->payeurService->supprimerPayeur($idPayeur);
            MessageFlash::ajouter("success", "Le payeur a bien été supprimé !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return PayeurController::rediriger("accueil");
    }

    /**
     * @Route ("/mettreAJourPayeur/{idPayeur}", GET)
     *
     * @param int $idPayeur
     * @return Response
     */
    public function afficherFormulaireMiseAJour(int $idPayeur): Response
    {
        try {
            $this->droitService->verifierDroits();
            $payeur = $this->payeurService->recupererParIdentifiant($idPayeur);
            return PayeurController::afficherTwig("payeur/mettreAJour.twig", ["payeur" => $payeur]);
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return PayeurController::rediriger("accueil");
        }
    }

    /**
     * @Route ("/mettreAJourPayeur/{idPayeur}", POST)
     *
     * @return Response
     */
    public function mettreAJour(): Response
    {
        try {
            $payeur = [
                "idPayeur" => $_POST["idPayeur"],
                "libPayeur" => strcmp($_POST["libPayeur"], "") == 0 ? null : $_POST["libPayeur"]
            ];
            $this->payeurService->modifierPayeur($payeur);
            MessageFlash::ajouter("success", "Le payeur a bien été modifié !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return PayeurController::rediriger("accueil");
    }
}