<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\EtatServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use Symfony\Component\HttpFoundation\Response;

class EtatController extends GenericController
{
    public function __construct(
        private readonly EtatServiceInterface $etatService
    )
    {
    }

    public function afficherListe(): Response{
        $etats = $this->etatService->recupererEtats();
        return EtatController::afficherTwig("etat/listeEtats.twig", ["etats" => $etats]);
    }

    public function afficherFormulaireMiseAJour(int $idEtat): Response{
        try {
            $etat = $this->etatService->recupererParIdentifiant($idEtat);
            return EtatController::afficherTwig("etat/mettreAJour.twig", ["etat" => $etat]);
        } catch (ServiceException $exception){
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return EtatController::rediriger("accueil");
        }
    }

    public function mettreAJour(): Response {
        try {
            $etat = [
                "idEtat" => $_POST["idEtat"],
                "libEtat" => strcmp($_POST["libEtat"], "") == 0 ? null : $_POST["libEtat"]
            ];
            $this->etatService->modifierEtat($etat);
            MessageFlash::ajouter("success", "L'état a bien été modifié !");
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