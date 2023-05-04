<?php

namespace App\Sensei\Controller;

use App\Sensei\Service\VoeuServiceInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class VoeuController extends GenericController
{

    public function __construct(
        private readonly VoeuServiceInterface $voeuService
    )
    {
    }

    public function exporterEnCSV(int $idIntervenant, string $nomFichier){
        $response = $this->voeuService->creerUnCSV($idIntervenant, $nomFichier);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,"$nomFichier.csv");
        file_put_contents("../ressources/temp/temp.csv", "");
        return $response;
    }
}