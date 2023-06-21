<?php

namespace App\Sensei\Service;

use App\Sensei\Lib\ConnexionUtilisateur;
use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\DroitRepository;
use App\Sensei\Service\Exception\ServiceException;

class DroitService implements DroitServiceInterface
{

    public function __construct(
        private readonly DroitRepository $droitRepository,
        private readonly ConnexionUtilisateur $connexionUtilisateur
    )
    {
    }

    /**
     * @param int $idDroit
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idDroit): AbstractDataObject
    {
        $droit = $this->droitRepository->recupererParClePrimaire($idDroit);
        if (!isset($droit)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $droit;
        }
    }

    /**
     * @throws ServiceException
     */
    public function creerDroit(array $droit)
    {
        if (empty($droit)){
            throw new ServiceException("Aucune information fournie !");
        }
        $this->droitRepository->ajouterSansIdDroit($droit);
    }

    public function recupererDroits(): array
    {
        return $this->droitRepository->recuperer();
    }

    public function supprimerDroit(int $idDroit)
    {
        $this->droitRepository->supprimer($idDroit);
    }

    /**
     * @throws ServiceException
     */
    public function modifierDroit(array $droit)
    {
        $objet = $this->droitRepository->recupererParClePrimaire($droit["idDroit"]);
        if (!isset($objet)) {
            throw new ServiceException("Aucun droit trouvé pour cet identifiant !");
        }

        $objet->setIdDroit($droit["idDroit"]);
        $objet->setTypeDroit($droit["typeDroit"]);

        $this->droitRepository->mettreAJour($objet);
    }

    /**
     * @throws ServiceException
     */
    public function verifierDroits(){
        $avoirLesPermissions = $this->connexionUtilisateur->estAdminOuChef();
        if (!$avoirLesPermissions){
            throw new ServiceException("Vous n'avez pas les permissions !");
        }
    }

    /**
     * @throws ServiceException
     */
    public function verifierDroitsPageVoeux(int $idEtat){
        if ($idEtat < 2){
            throw new ServiceException("La phase de voeux n'a pas encore débutée !");
        }
    }
}