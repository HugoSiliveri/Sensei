<?php

namespace App\Sensei\Controller;

use App\Sensei\Service\VoeuServiceInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class VoeuController extends GenericController
{

    public function __construct(
        private readonly VoeuServiceInterface $voeuService
    )
    {
    }

    public function exporterEnCSV()
    {
        $nomFichier = "test";
        $idIntervenant = 3637;

        $chemin = __DIR__ . '/../../ressources/temp/temp.csv';
        $voeux = $this->voeuService->recupererVueParIntervenant($idIntervenant);

        $f = fopen($chemin, 'w');

        if ($f) {
            $entete = ["millesime", "idUSReferentiel", "libUS", "typeIntervention", "volumeHoraire"];

            fputcsv($f, $entete, ";");

            foreach ($voeux as $voeu) {
                $voeuSansId = [
                    $voeu["millesime"],
                    $voeu["idUSReferentiel"],
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
    }
}