<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\DeclarationServiceServiceInterface;
use App\Sensei\Service\DroitServiceInterface;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\IntervenantServiceInterface;
use App\Sensei\Service\InterventionServiceInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DeclarationServiceController extends GenericController
{


    public function __construct(
        private readonly DeclarationServiceServiceInterface $declarationServiceService,
        private readonly IntervenantServiceInterface        $intervenantService,
        private readonly DroitServiceInterface              $droitService,
        private readonly InterventionServiceInterface       $interventionService
    )
    {
    }

//    public function afficherFormulaireMiseAJour(int $idDeclarationService): Response
//    {
//        try {
//            $this->droitService->verifierDroits();
//            $declarationService = $this->declarationServiceService->recupererParIdentifiant($idDeclarationService);
//            $intervenant = $this->intervenantService->recupererParIdentifiant($declarationService->getIdIntervenant());
//            return IntervenantController::afficherTwig("declarationService/mettreAJour.twig", [
//                "declarationService" => $declarationService,
//                "intervenant" => $intervenant]);
//        } catch (ServiceException $exception) {
//            if (strcmp($exception->getCode(), "danger") == 0) {
//                MessageFlash::ajouter("danger", $exception->getMessage());
//            } else {
//                MessageFlash::ajouter("warning", $exception->getMessage());
//            }
//            return ServiceAnnuelController::rediriger("afficherListeIntervenants");
//        }
//    }

    /**
     * @Route ("/mettreAJourDeclarationService/{idIntervenant}", POST)
     *
     * @param int $idIntervenant
     * @return Response
     */
    public function mettreAJourPourUnIntervenant(int $idIntervenant): Response
    {
        try {
            $declarationsServices = $this->declarationServiceService->recupererVueParIdIntervenantAnnuel($idIntervenant, $_POST["millesime"]);


            for ($i = 0; $i < count($declarationsServices); $i++) {
                // Comme des services peuvent partager plusieurs interventions alors la modification d'un
                // va entrainer la modification des autres, on va donc en créer un nouveau sinon renvoyer
                // un existant
                $interventionTab = [
                    "idIntervention" => $declarationsServices[$i]["idIntervention"],
                    "typeIntervention" => $_POST["typeIntervention$i"],
                    "numeroGroupeIntervention" => $_POST["numGroupeIntervention$i"],
                    "volumeHoraire" => $_POST["volumeHoraire$i"],
                ];
                $intervention = $this->interventionService->recupererParInfos($interventionTab);

                if (count($intervention) == 0) {
                    $this->interventionService->creerIntervention($interventionTab);
                    $intervention = $this->interventionService->recupererParInfos($interventionTab);
                }
                $declarationServiceTab = [
                    "idDeclarationService" => $declarationsServices[$i]["idDeclarationService"],
                    "idIntervenant" => $declarationsServices[$i]["idIntervenant"],
                    "idUniteServiceAnnee" => $declarationsServices[$i]["idUniteServiceAnnee"],
                    "mode" => $declarationsServices[$i]["mode"],
                    "idIntervention" => $intervention["idIntervention"]
                ];
                $this->declarationServiceService->modifierDeclarationService($declarationServiceTab);
            }
            MessageFlash::ajouter("success", "Les services ont bien été modifiés !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return IntervenantController::rediriger("accueil");
    }

    /**
     * @Route (pas de route)
     *
     * @param int $idDeclarationService
     * @return Response
     */
    public function supprimer(int $idDeclarationService): Response
    {
        try {
            $this->droitService->verifierDroits();
            $this->declarationServiceService->supprimerDeclarationService($idDeclarationService);
            MessageFlash::ajouter("success", "Le service a bien été enlevé !");
        } catch (ServiceException $exception) {
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return EmploiController::rediriger("accueil");
    }

    /**
     * @Route ("/export/{idIntervenant}", GET)
     *
     * @param int $idIntervenant
     * @return Response
     */
    public function exporterEnCSV(int $idIntervenant): Response
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