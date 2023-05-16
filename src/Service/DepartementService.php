<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\DepartementRepository;
use App\Sensei\Service\Exception\ServiceException;

class DepartementService implements DepartementServiceInterface
{
    public function __construct(
        private DepartementRepository $departementRepository,
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

    public function recupererDepartements()
    {
        return $this->departementRepository->recuperer();
    }

    /**
     * @throws ServiceException
     */
    public function recupererParLibelle(string $lib)
    {
        if (strcmp("", $lib) || strcmp(" ", $lib)) {
            throw new ServiceException("Le département n'est spécifié !");
        } else {
            $departement = $this->departementRepository->recupererParLibelle($lib);
            if (count($departement) != 1) {
                throw new ServiceException("Aucun/plusieurs département(s) n'a/ont été trouvé !");
            }
            return $departement[0];
        }
    }
    
    public function creerDepartement(array $departement) {
        $this->departementRepository->ajouterSansIdDepartement($departement);
    }

    public function supprimerDepartement(int $idDepartement) {
        $this->departementRepository->supprimer($idDepartement);
    }

    /**
     * @throws ServiceException
     */
    public function modifierDepartement(array $departement) {
        $objet = $this->departementRepository->recupererParClePrimaire($departement["idDepartement"]);
        if (!isset($objet)){
            throw new ServiceException("Aucune departement trouvée pour cet identifiant !");
        }

        $objet->setIdDepartement($departement["idDepartement"]);
        $objet->setLibDepartement($departement["libDepartement"]);
        $objet->setCodeLettre($departement["codeLettre"]);
        $objet->setReportMax($departement["reportMax"]);
        $objet->setIdComposante($departement["idComposante"]);
        $objet->setIdEtat($departement["idEtat"]);

        $this->departementRepository->mettreAJour($objet);
    }
}