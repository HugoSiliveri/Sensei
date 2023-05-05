<?php

namespace App\Sensei\Service;

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
     * @param int $idUniteServiceAnnee
     * @return array
     * @throws ServiceException
     */
    public function recupererParIdUSA(int $idUniteServiceAnnee): array
    {
        if (!isset($idUniteServiceAnnee)) {
            throw new ServiceException("L'identifiant n'est pas défini !");
        } else {
            return $this->voeuRepository->recupererParIdUSA($idUniteServiceAnnee);
        }
    }

    public function creerUnCSV(int $idIntervenant, string $nomFichier)
    {
        $chemin = __DIR__ . '/../../ressources/temp/temp.csv';
        $voeux = $this->recupererVueParIntervenant($idIntervenant);
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
            return new BinaryFileResponse($chemin);
        } else {
            return new Response("", 404);
        }
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
}