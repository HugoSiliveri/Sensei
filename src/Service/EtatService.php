<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\EtatRepository;
use App\Sensei\Service\Exception\ServiceException;

class EtatService implements EtatServiceInterface
{
    public function __construct(
        private EtatRepository $etatRepository,
    )
    {
    }

    /**
     * @param int $idEtat
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idEtat): AbstractDataObject
    {
        $etat = $this->etatRepository->recupererParClePrimaire($idEtat);
        if (!isset($etat)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $etat;
        }
    }

    public function recupererEtats() {
        return $this->etatRepository->recuperer();
    }

    /**
     * @throws ServiceException
     */
    public function modifierEtat(array $etat) {
        $objet = $this->etatRepository->recupererParClePrimaire($etat["idEtat"]);
        if (!isset($objet)){
            throw new ServiceException("Aucun etat trouvé pour cet identifiant !");
        }

        $objet->setIdEtat($etat["idEtat"]);
        $objet->setLibEtat($etat["libEtat"]);

        $this->etatRepository->mettreAJour($objet);
    }
}