<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\DepartementRepository;
use App\Sensei\Model\Repository\IntervenantRepository;
use App\Sensei\Model\Repository\ServiceAnnuelRepository;
use App\Sensei\Service\Exception\ServiceException;

class DepartementService implements DepartementServiceInterface
{
    public function __construct(
        private readonly DepartementRepository   $departementRepository,
        private readonly ServiceAnnuelRepository $serviceAnnuelRepository,
        private readonly IntervenantRepository   $intervenantRepository
    )
    {
    }

    /**
     * @param int $idDepartement
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idDepartement): AbstractDataObject
    {
        $departement = $this->departementRepository->recupererParClePrimaire($idDepartement);
        if (!isset($departement)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $departement;
        }

    }

    public function recupererDepartements(): array
    {
        return $this->departementRepository->recuperer();
    }

    /**
     * @throws ServiceException
     */
    public function recupererParLibelle(string $lib)
    {
        if (strcmp("", $lib) == 0 || strcmp(" ", $lib) == 0) {
            throw new ServiceException("Le département n'est spécifié !");
        } else {
            $departement = $this->departementRepository->recupererParLibelle($lib);
            if (count($departement) != 1) {
                throw new ServiceException("Aucun/plusieurs département(s) n'a/ont été trouvé !");
            }
            return $departement[0];
        }
    }

    /**
     * @throws ServiceException
     */
    public function creerDepartement(array $departement)
    {
        if (empty($departement)) {
            throw new ServiceException("Aucune information fournie !");
        }
        $this->departementRepository->ajouterSansIdDepartement($departement);
    }

    public function supprimerDepartement(int $idDepartement)
    {
        $this->departementRepository->supprimer($idDepartement);
    }

    /**
     * @throws ServiceException
     */
    public function modifierDepartement(array $departement)
    {
        $objet = $this->departementRepository->recupererParClePrimaire($departement["idDepartement"]);
        if (!isset($objet)) {
            throw new ServiceException("Aucune département trouvée pour cet identifiant !");
        }

        $objet->setIdDepartement($departement["idDepartement"]);
        $objet->setLibDepartement($departement["libDepartement"]);
        $objet->setCodeLettre($departement["codeLettre"]);
        $objet->setReportMax($departement["reportMax"]);
        $objet->setIdComposante($departement["idComposante"]);
        $objet->setIdEtat($departement["idEtat"]);

        $this->departementRepository->mettreAJour($objet);
    }

    /**
     * @throws ServiceException
     */
    public function changerEtat(int $idDepartement, int $idEtat)
    {
        if ($idEtat < 1 || $idEtat > 3) {
            throw new ServiceException("L'état choisi n'existe pas !");
        }
        $this->departementRepository->changerEtat($idDepartement, $idEtat);
    }

    /**
     * @throws ServiceException
     */
    public function verifierDroitsPourGestion(int $idIntervenant, int $idDepartement)
    {
        $serviceAnnuel = $this->serviceAnnuelRepository->recupererParIntervenantAnnuelPlusRecent($idIntervenant);
        if ($serviceAnnuel->getIdDepartement() != $idDepartement) {
            throw new ServiceException("Vous n'appartenez pas au département que vous souhaitez modifier !");
        }
        $intervenant = $this->intervenantRepository->recupererParClePrimaire($idIntervenant);
        if ($intervenant->getIdDroit() > 2) {
            throw new ServiceException("Vous n'avez pas les permissions pour réaliser la modification !");
        }
    }
}