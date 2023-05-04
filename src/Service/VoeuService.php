<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\VoeuRepository;
use App\Sensei\Service\Exception\ServiceException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class VoeuService implements VoeuServiceInterface
{
    public function __construct(
        private VoeuRepository $voeuRepository,
    )
    {
    }

    /**
     * @param int $idIntervenant
     * @return array
     * @throws ServiceException
     */
    public function recupererVueParIntervenant(int $idIntervenant): array
    {
        if (!isset($idIntervenant)) {
            throw new ServiceException("L'identifiant n'est pas défini !");
        } else {
            return $this->voeuRepository->recupererVueParIntervenant($idIntervenant);
        }
    }

    public function creerUnCSV(int $idIntervenant, string $nomFichier){
        $voeux = $this->recupererVueParIntervenant($idIntervenant);

        $f = fopen('../ressources/temp/temp.csv', 'w');
        if ($f){
            $entete = ["millesime", "idUSReferentiel", "libUS", "typeIntervention", "volumeHoraire"];

            fputcsv($f, $entete, ";");

            foreach ($voeux as $voeu){
                $voeuSansId = [
                    $voeu["millesime"],
                    $voeu["idUSReferentiel"],
                    $voeu["libUS"],
                    $voeu["typeIntervention"],
                    $voeu["volumeHoraire"]
                ];

                fputcsv($f, $voeuSansId, ";");
            }

            rewind($f);
            //$response = new Response(stream_get_contents($f));
            fclose($f);

            return new BinaryFileResponse("../ressources/temp/temp.csv");
        } else {
            return new Response("", 404);
        }


//        $response->headers->set('Content Type', 'text/csv');
//        $response->headers->set('Content Disposition', 'attachment; filename="testing.csv"');
//        var_dump($response);

    }
}