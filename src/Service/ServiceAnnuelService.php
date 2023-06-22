<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\DataObject\ServiceAnnuel;
use App\Sensei\Model\Repository\ServiceAnnuelRepository;
use App\Sensei\Service\Exception\ServiceException;

class ServiceAnnuelService implements ServiceAnnuelServiceInterface
{
    public function __construct(
        private readonly ServiceAnnuelRepository $serviceAnnuelRepository,
    )
    {
    }

    /**
     * @param int $idIntervenant
     * @return array
     */
    public function recupererParIntervenant(int $idIntervenant): array
    {
        return $this->serviceAnnuelRepository->recupererParIntervenant($idIntervenant);
    }

    public function recupererParIdentifiant(int $idServiceAnnuel): ?AbstractDataObject
    {
        return $this->serviceAnnuelRepository->recupererParClePrimaire($idServiceAnnuel);
    }

    /**
     * @param int $idIntervenant
     * @param int $millesime
     * @return AbstractDataObject|null
     */
    public function recupererParIntervenantAnnuel(int $idIntervenant, int $millesime): ?AbstractDataObject
    {

        return $this->serviceAnnuelRepository->recupererParIntervenantAnnuel($idIntervenant, $millesime);
    }

    public function recupererParIntervenantAnnuelPlusRecent(int $idIntervenant): ?AbstractDataObject
    {
        return $this->serviceAnnuelRepository->recupererParIntervenantAnnuelPlusRecent($idIntervenant);
    }

    /**
     * @throws ServiceException
     */
    public function modifierServiceAnnuel(array $serviceAnnuel)
    {
        $objet = $this->serviceAnnuelRepository->recupererParClePrimaire($serviceAnnuel["idServiceAnnuel"]);
        if (!isset($objet)) {
            throw new ServiceException("Aucun service trouvé pour cet identifiant !");
        }
        $objet->setIdServiceAnnuel($serviceAnnuel["idServiceAnnuel"]);
        $objet->setIdDepartement($serviceAnnuel["idDepartement"]);
        $objet->setIdIntervenant($serviceAnnuel["idIntervenant"]);
        $objet->setMillesime($serviceAnnuel["millesime"]);
        $objet->setIdEmploi($serviceAnnuel["idEmploi"]);
        $objet->setServiceStatuaire($serviceAnnuel["serviceStatuaire"]);
        $objet->setServiceFait($serviceAnnuel["serviceFait"]);
        $objet->setDelta($serviceAnnuel["delta"]);
        $objet->setDeleted($serviceAnnuel["deleted"]);

        $this->serviceAnnuelRepository->mettreAJour($objet);
    }

    /**
     * @throws ServiceException
     */
    public function recupererPlusRecentDuDepartement(int $idDepartement){
        $serviceAnnuel = $this->serviceAnnuelRepository->recupererPlusRecentDuDepartement($idDepartement);
        if (!isset($serviceAnnuel)){
            throw new ServiceException("La table des services est vide !");
        }
        return $serviceAnnuel;
    }

    public function recupererParDepartementAnnuel(int $idDepartement, int $annee): ?array
    {
        return $this->serviceAnnuelRepository->recupererParDepartementAnnuel($idDepartement, $annee);
    }

    public function renouvellerServiceAnnuel(ServiceAnnuel $serviceAnnuel, int $annee){
        $serviceAnnuel->setMillesime($annee);
        $serviceAnnuel->setServiceFait(0);
        $serviceAnnuel->setDelta(0);
        $this->serviceAnnuelRepository->ajouterSansIdServiceAnnuel($serviceAnnuel);
    }

    /**
     * @throws ServiceException
     */
    public function creerServiceAnnuel(array $serviceAnnuel){
        if (empty($serviceAnnuel)){
            throw new ServiceException("Aucune information fournie !");
        }
        $objet = $this->serviceAnnuelRepository->creerDepuisTableau($serviceAnnuel);
        $this->serviceAnnuelRepository->ajouterSansIdServiceAnnuel($objet);
    }
}