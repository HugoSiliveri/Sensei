<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\ConnexionUtilisateurInterface;
use App\Sensei\Service\IntervenantServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class IntervenantController extends GenericController
{

    public function __construct(
        private IntervenantServiceInterface $intervenantService,
        private ConnexionUtilisateurInterface $connexionUtilisateur
    )
    {
    }

    /**
     * Méthode qui affiche la liste des utilisateurs.
     *
     * @return Response
     */
    public function afficherListe(): Response
    {
        $intervenants = $this->intervenantService->recupererIntervenants();
        return GenericController::afficherTwig("intervenant/listeIntervenants.twig", ["intervenants" => $intervenants]);
    }

}