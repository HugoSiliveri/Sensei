<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\UniteServiceAnnee;
use App\Sensei\Model\Repository\UniteServiceAnneeRepository;
use App\Sensei\Service\Exception\ServiceException;

class UniteServiceAnneeService implements UniteServiceAnneeServiceInterface
{
    public function __construct(
        private readonly UniteServiceAnneeRepository $uniteServiceAnneeRepository,
    )
    {
    }

    /**
     * @param int $idUniteServiceAnnee
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idUniteServiceAnnee): AbstractDataObject
    {
        $uniteServiceAnnee = $this->uniteServiceAnneeRepository->recupererParClePrimaire($idUniteServiceAnnee);
        if (!isset($uniteServiceAnnee)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $uniteServiceAnnee;
        }
    }

    /**
     * @param int $idUniteService
     * @return array
     */
    public function recupererParUniteService(int $idUniteService): array
    {
        return $this->uniteServiceAnneeRepository->recupererParUniteService($idUniteService);
    }

    /**
     * @throws ServiceException
     */
    public function creerUniteServiceAnnee(array $uniteServiceAnnee)
    {
        if (empty($uniteServiceAnnee)){
            throw new ServiceException("Aucune information fournie !");
        }
        $this->uniteServiceAnneeRepository->ajouterSansIdUniteServiceAnnee($uniteServiceAnnee);
    }

    /**
     * @throws ServiceException
     */
    public function modifierUniteServiceAnnee(array $uniteServiceAnnee)
    {
        $objet = $this->uniteServiceAnneeRepository->recupererParClePrimaire($uniteServiceAnnee["idUniteServiceAnnee"]);
        if (!isset($objet)) {
            throw new ServiceException("Aucune unité de service trouvée pour cet identifiant !");
        }

        $objet->setIdUniteServiceAnnee($uniteServiceAnnee["idUniteServiceAnnee"]);
        $objet->setIdDepartement($uniteServiceAnnee["idDepartement"]);
        $objet->setIdUniteService($uniteServiceAnnee["idUniteService"]);
        $objet->setLibUSA($uniteServiceAnnee["libUSA"]);
        $objet->setMillesime($uniteServiceAnnee["millesime"]);
        $objet->setHeuresCM($uniteServiceAnnee["heuresCM"]);
        $objet->setNbGroupesCM($uniteServiceAnnee["nbGroupesCM"]);
        $objet->setHeuresTD($uniteServiceAnnee["heuresTD"]);
        $objet->setNbGroupesTD($uniteServiceAnnee["nbGroupesTD"]);
        $objet->setHeuresTP($uniteServiceAnnee["heuresTP"]);
        $objet->setNbGroupesTP($uniteServiceAnnee["nbGroupesTP"]);
        $objet->setHeuresStage($uniteServiceAnnee["heuresStage"]);
        $objet->setNbGroupesStage($uniteServiceAnnee["nbGroupesStage"]);
        $objet->setHeuresTerrain($uniteServiceAnnee["heuresTerrain"]);
        $objet->setNbGroupesTerrain($uniteServiceAnnee["nbGroupesTerrain"]);
        $objet->setHeuresInnovationPedagogique($uniteServiceAnnee["heuresInnovationPedagogique"]);
        $objet->setNbGroupesInnovationPedagogique($uniteServiceAnnee["nbGroupesInnovationPedagogique"]);
        $objet->setValidite($uniteServiceAnnee["validite"]);
        $objet->setDeleted($uniteServiceAnnee["deleted"]);

        $this->uniteServiceAnneeRepository->mettreAJour($objet);
    }

    public function recupererUnitesServicesPourUneAnneePourUnDepartement(int $anneeActuelle, int $idDepartement): array
    {
        return $this->uniteServiceAnneeRepository->recupererUnitesServicesPourUneAnneePourUnDepartement($anneeActuelle, $idDepartement);
    }

    public function recupererUnitesServicesAnneeUniquementColoration(int $anneeActuelle, int $idDepartement): array
    {
        return $this->uniteServiceAnneeRepository->recupererUniteServiceAnneeUniquementColoration($anneeActuelle, $idDepartement);
    }
    
    public function renouvelerUniteServiceAnnee(UniteServiceAnnee $usa, int $annee){
        $usa->setMillesime($annee);
        $tab = [
            "idDepartement" => $usa->getIdDepartement(),
            "idUniteService" => $usa->getIdUniteServiceAnnee(),
            "libUSA" => $usa->getLibUSA(),
            "millesime" => $usa->getMillesime(),
            "heuresCM" => $usa->getHeuresCM(),
            "nbGroupesCM" => $usa->getNbGroupesCM(),
            "heuresTD" => $usa->getHeuresTD(),
            "nbGroupesTD" => $usa->getNbGroupesTD(),
            "heuresTP" => $usa->getHeuresTP(),
            "nbGroupesTP" => $usa->getNbGroupesTP(),
            "heuresStage" => $usa->getHeuresStage(),
            "nbGroupesStage" => $usa->getNbGroupesStage(),
            "heuresTerrain" => $usa->getHeuresTerrain(),
            "nbGroupesTerrain" => $usa->getNbGroupesTerrain(),
            "heuresInnovationPedagogique" => $usa->getHeuresInnovationPedagogique(),
            "nbGroupesInnovationPedagogique" => $usa->getNbGroupesInnovationPedagogique(),
            "validite" => $usa->getValidite(),
            "deleted" => $usa->getDeleted()
        ];
        $this->uniteServiceAnneeRepository->ajouterSansIdUniteServiceAnnee($tab);
    }
}