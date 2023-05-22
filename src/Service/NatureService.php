<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\NatureRepository;
use App\Sensei\Service\Exception\ServiceException;

class NatureService implements NatureServiceInterface
{
    public function __construct(
        private NatureRepository $natureRepository,
    )
    {
    }

    /**
     * @param int $idNature
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idNature): AbstractDataObject
    {
        $nature = $this->natureRepository->recupererParClePrimaire($idNature);
        if (!isset($nature)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $nature;
        }
    }

    public function creerNature(array $nature)
    {
        $this->natureRepository->ajouterSansIdNature($nature);
    }

    public function recupererNatures()
    {
        return $this->natureRepository->recuperer();
    }

    public function supprimerNature(int $idNature)
    {
        $this->natureRepository->supprimer($idNature);
    }

    /**
     * @throws ServiceException
     */
    public function modifierNature(array $nature)
    {
        $objet = $this->natureRepository->recupererParClePrimaire($nature["idNature"]);
        if (!isset($objet)) {
            throw new ServiceException("Aucune nature trouvée pour cet identifiant !");
        }

        $objet->setIdNature($nature["idNature"]);
        $objet->setLibNature($nature["libNature"]);

        $this->natureRepository->mettreAJour($objet);
    }
}