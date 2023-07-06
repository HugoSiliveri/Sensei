<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\InterventionRepository;
use App\Sensei\Service\Exception\ServiceException;

class InterventionService implements InterventionServiceInterface
{
    public function __construct(
        private readonly InterventionRepository $interventionRepository,
    )
    {
    }

    /**
     * @throws ServiceException
     */
    public function modifierIntervention(array $intervention)
    {
        $objet = $this->interventionRepository->recupererParClePrimaire($intervention["idIntervention"]);
        if (!isset($objet)) {
            throw new ServiceException("Aucune intervention trouvée pour cet identifiant !");
        }
        $objet->setIdIntervention($intervention["idIntervention"]);
        $objet->setTypeIntervention($intervention["typeIntervention"]);
        $objet->setNumeroGroupeIntervention($intervention["numeroGroupeIntervention"]);
        $objet->setVolumeHoraire($intervention["volumeHoraire"]);

        $this->interventionRepository->mettreAJour($objet);
    }

    /**
     * @throws ServiceException
     */
    public function creerIntervention(array $intervention)
    {
        if (empty($intervention)) {
            throw new ServiceException("Aucune information fournie !");
        }
        $this->interventionRepository->ajouterSansIdIntervention($intervention);
    }

    /**
     * @throws ServiceException
     */
    public function recupererParInfos(array $intervention): array
    {
        if (empty($intervention)) {
            throw new ServiceException("Aucune information disponible !");
        }
        return $this->interventionRepository->recupererParInfos($intervention);
    }

    /**
     * @param int $idIntervention
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idIntervention): AbstractDataObject
    {
        $intervention = $this->interventionRepository->recupererParClePrimaire($idIntervention);
        if (!isset($intervention)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $intervention;
        }
    }

    /**
     * @throws ServiceException
     */
    public function recupererDernierIntervention(): AbstractDataObject
    {
        $intervention = $this->interventionRepository->recupererDernierIntervention();
        if (!isset($intervention)) {
            throw new ServiceException("La table des interventions est vide !");
        }
        return $intervention;
    }
}