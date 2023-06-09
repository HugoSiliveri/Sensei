<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\StatutRepository;
use App\Sensei\Service\Exception\ServiceException;

class StatutService implements StatutServiceInterface
{
    public function __construct(
        private readonly StatutRepository $statutRepository,
    )
    {
    }

    /**
     * @param int $idStatut
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idStatut): AbstractDataObject
    {
        $statut = $this->statutRepository->recupererParClePrimaire($idStatut);
        if (!isset($statut)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $statut;
        }
    }

    /**
     * @throws ServiceException
     */
    public function creerStatut(array $statut)
    {
        if (empty($statut)){
            throw new ServiceException("Aucune information fournie !");
        }
        $this->statutRepository->ajouterSansIdStatut($statut);
    }

    public function recupererStatuts(): array
    {
        return $this->statutRepository->recuperer();
    }

    public function supprimerStatut(int $idStatut)
    {
        $this->statutRepository->supprimer($idStatut);
    }

    /**
     * @throws ServiceException
     */
    public function modifierStatut(array $statut)
    {
        $objet = $this->statutRepository->recupererParClePrimaire($statut["idStatut"]);
        if (!isset($objet)) {
            throw new ServiceException("Aucun statut trouvé pour cet identifiant !");
        }

        $objet->setIdStatut($statut["idStatut"]);
        $objet->setLibStatut($statut["libStatut"]);
        $objet->setNbHeures($statut["nbHeures"]);

        $this->statutRepository->mettreAJour($objet);
    }
}