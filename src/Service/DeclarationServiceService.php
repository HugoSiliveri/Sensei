<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\DeclarationServiceRepository;
use App\Sensei\Model\Repository\ServiceAnnuelRepository;
use App\Sensei\Service\Exception\ServiceException;

class DeclarationServiceService implements DeclarationServiceServiceInterface
{

    public function __construct(
        private readonly DeclarationServiceRepository $declarationServiceRepository,
        private readonly ServiceAnnuelRepository $serviceAnnuelRepository,
    )
    {
    }

    /**
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idDeclarationService): AbstractDataObject
    {
        $intervenant = $this->declarationServiceRepository->recupererParClePrimaire($idDeclarationService);
        if (!isset($intervenant)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $intervenant;
        }
    }

    /**
     * @throws ServiceException
     */
    public function modifierDeclarationService(array $declarationService)
    {
        $objet = $this->declarationServiceRepository->recupererParClePrimaire($declarationService["idDeclarationService"]);
        if (!isset($objet)) {
            throw new ServiceException("Aucun service trouvé pour cet identifiant !");
        }

        $objet->setIdDeclarationService($declarationService["idDeclarationService"]);
        $objet->setIdIntervenant($declarationService["idIntervenant"]);
        $objet->setIdUniteServiceAnnee($declarationService["idUniteServiceAnnee"]);
        $objet->setMode($declarationService["mode"]);
        $objet->setIdIntervention($declarationService["idIntervention"]);

        $this->declarationServiceRepository->mettreAJour($objet);
    }

    public function supprimerDeclarationService(int $idDeclarationService)
    {
        $this->declarationServiceRepository->supprimer($idDeclarationService);
    }

    public function recupererParIdIntervenant(int $idIntervenant): array
    {
        return $this->declarationServiceRepository->recupererParIdIntervenant($idIntervenant);
    }

    /**
     * @param int $idIntervenant
     * @return array
     */
    public function recupererVueParIdIntervenant(int $idIntervenant): array
    {
        return $this->declarationServiceRepository->recupererVueParIdIntervenant($idIntervenant);
    }

    /**
     * @param int $idIntervenant
     * @param int $annee
     * @return array
     */
    public function recupererVueParIdIntervenantAnnuel(int $idIntervenant, int $annee): array
    {
        return $this->declarationServiceRepository->recupererVueParIdIntervenantAnnuel($idIntervenant, $annee);
    }

    /**
     * @param int $idUniteServiceAnnee
     * @return array
     */
    public function recupererParIdUSA(int $idUniteServiceAnnee): array
    {
        return $this->declarationServiceRepository->recupererParIdUSA($idUniteServiceAnnee);
    }

    public function verifierPhaseDeVoeu(int $annee, int $idDepartement): bool{
        $servicesAnnuels = $this->serviceAnnuelRepository->recupererParDepartementAnnuel($idDepartement, $annee);
        $taille = count($servicesAnnuels);
        $taille2 = 0;
        foreach ($servicesAnnuels as $serviceAnnuel) {
            $declarationService = $this->declarationServiceRepository->recupererVueParIdIntervenantAnnuel($serviceAnnuel->getIdIntervenant(), $annee);
            if (empty($declarationService)){
                $taille2 += 1;
            }
        }
        // Si tous les services ne sont pas déclarés pour chaque intervenant alors la phase de voeu n'est pas terminé
        if ($taille == $taille2){
            return false;
        }
        return true;
    }
}