<?php

namespace App\Sensei\Controller;

use App\Sensei\Service\UniteServiceServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class UniteServiceController extends GenericController
{
    public function __construct(
        private UniteServiceServiceInterface $uniteServiceService,
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
        $unitesServices = $this->uniteServiceService->recupererUnitesServices();
        return GenericController::afficherTwig("uniteService/listeUnitesServices.twig", ["unitesServices" => $unitesServices]);
    }
}