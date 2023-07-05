<?php

namespace App\Sensei\Controller;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\IntervenantServiceInterface;
use App\Sensei\Service\InterventionServiceInterface;
use App\Sensei\Service\UniteServiceAnneeServiceInterface;
use App\Sensei\Service\UniteServiceServiceInterface;
use App\Sensei\Service\VoeuServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class VoeuController extends GenericController
{

    public function __construct(
        private readonly VoeuServiceInterface $voeuService,
        private readonly IntervenantServiceInterface $intervenantService,
        private readonly UniteServiceServiceInterface $uniteServiceService,
        private readonly UniteServiceAnneeServiceInterface $uniteServiceAnneeService,
        private readonly InterventionServiceInterface $interventionService
    )
    {
    }

    /**
     * @Route ("/services", POST)
     *
     * @return Response
     */
    public function mettreAJourVoeux(): Response
    {
        try {
            // TODO : ENLEVER LES VOEUX QUI ONT ETE SUPPRIMER DE LA LISTE PAR L'UTILISATEUR
            $millesime = $_POST["millesime"];
            $idIntervenant = $_POST["idIntervenant"];
            $nbElements = (count($_POST)-2)/4; // -2 pour idIntervenant et millesime

            $voeux = $this->voeuService->recupererParIntervenantAnnuel($idIntervenant, $millesime);

            for ($i=0; $i < $nbElements; $i++){
                $uniteService = $this->uniteServiceService->recupererParIdUSReferentiel($_POST["idUSReferentiel".$i]);
                $uniteServiceAnnee = $this->uniteServiceAnneeService->recupererParUniteServiceAvecAnnee($uniteService->getIdUniteService(), $millesime);

                $interventionTab = [
                    "typeIntervention" => $_POST["typeIntervention".$i],
                    "volumeHoraire" => $_POST["volumeHoraire".$i],
                    "numeroGroupeIntervention" => $_POST["numGroupeIntervention".$i]
                ];

                if ($_POST["volumeHoraire".$i] != 0){
                    $existe = false;

                    for ($j=0; $j < count($voeux); $j++){
                        $interventionVoeu = $this->interventionService->recupererParIdentifiant($voeux[$j]->getIdIntervention());

                        if ($uniteServiceAnnee->getIdUniteServiceAnnee() == $voeux[$j]->getIdUniteServiceAnnee()){
                            if ($interventionVoeu->getTypeIntervention() == $interventionTab["typeIntervention"]
                                && $interventionVoeu->getNumeroGroupeIntervention() == $interventionTab["numeroGroupeIntervention"]){

                                $existe = true;
                                // On regarde si le volume horaire a été modifié
                                if ($interventionVoeu->getVolumeHoraire() != $interventionTab["volumeHoraire"]){
                                    $interventionTab = [
                                        "idIntervention" => $interventionVoeu->getIdIntervention(),
                                        "typeIntervention" => $_POST["typeIntervention".$i],
                                        "volumeHoraire" => $_POST["volumeHoraire".$i],
                                        "numeroGroupeIntervention" => $_POST["numGroupeIntervention".$i]
                                    ];
                                    $this->interventionService->modifierIntervention($interventionTab);
                                }
                            }
                        }
                    }
                    if (!$existe){
                        $this->interventionService->creerIntervention($interventionTab);
                        $intervention = $this->interventionService->recupererDernierIntervention();

                        $voeuTab = [
                            "idIntervenant" => $_POST["idIntervenant"],
                            "idUniteServiceAnnee" => $uniteServiceAnnee->getIdUniteServiceAnnee(),
                            "idIntervention" => $intervention->getIdIntervention()
                        ];
                        $this->voeuService->creerVoeu($voeuTab);
                    }
                } else {

                    for ($j=0; $j < count($voeux); $j++){
                        $interventionVoeu = $this->interventionService->recupererParIdentifiant($voeux[$j]->getIdIntervention());

                        if ($uniteServiceAnnee->getIdUniteServiceAnnee() == $voeux[$j]->getIdUniteServiceAnnee()){
                            if ($interventionVoeu->getTypeIntervention() == $interventionTab["typeIntervention"]
                                && $interventionVoeu->getNumeroGroupeIntervention() == $interventionTab["numeroGroupeIntervention"]){

                                $this->voeuService->supprimerVoeu($voeux[$j]->getIdVoeu());
                            }
                        }
                    }
                }

            }
            MessageFlash::ajouter("success","Les voeux ont bien été saisies !");
        } catch (ServiceException $exception){
            if (strcmp($exception->getCode(), "danger") == 0) {
                MessageFlash::ajouter("danger", $exception->getMessage());
            } else {
                MessageFlash::ajouter("warning", $exception->getMessage());
            }
        }
        return VoeuController::rediriger("accueil");
    }
}