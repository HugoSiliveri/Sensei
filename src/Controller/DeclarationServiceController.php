<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\DeclarationServiceServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\IntervenantServiceInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DeclarationServiceController extends GenericController
{


    public function __construct(
        private readonly DeclarationServiceServiceInterface $declarationServiceService,
        private readonly IntervenantServiceInterface        $intervenantService
    )
    {
    }

    public function exporterEnCSV(int $idIntervenant)
    {
        try {
            $intervenant = $this->intervenantService->recupererParIdentifiant($idIntervenant);
            $nomFichier = $intervenant->getNom() . $intervenant->getPrenom();

            $chemin = __DIR__ . '/../../ressources/temp/temp.csv';
            $voeux = $this->declarationServiceService->recupererVueParIdIntervenant($idIntervenant);

            $f = fopen($chemin, 'w');

            if ($f) {
                $entete = ["millesime", "idUsReferentiel", "libUS", "typeIntervention", "volumeHoraire"];

                fputcsv($f, $entete, ";");

                foreach ($voeux as $voeu) {
                    $voeuSansId = [
                        $voeu["millesime"],
                        $voeu["idUsReferentiel"],
                        $voeu["libUS"],
                        $voeu["typeIntervention"],
                        $voeu["volumeHoraire"]
                    ];
                    fputcsv($f, $voeuSansId, ";");
                }
                fclose($f);

                $response = new BinaryFileResponse($chemin);
                $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, "$nomFichier.csv");

                return $response;
            } else {
                return new Response("", 404);
            }
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
            return IntervenantController::rediriger("afficherListeIntervenants");
        }

    }
}