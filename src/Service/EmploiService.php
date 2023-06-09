<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\EmploiRepository;
use App\Sensei\Service\Exception\ServiceException;

class EmploiService implements EmploiServiceInterface
{
    public function __construct(
        private readonly EmploiRepository $emploiRepository,
    )
    {
    }

    /**
     * @param int $idEmploi
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idEmploi): AbstractDataObject
    {
        $emploi = $this->emploiRepository->recupererParClePrimaire($idEmploi);
        if (!isset($emploi)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $emploi;
        }
    }

    /**
     * @throws ServiceException
     */
    public function creerEmploi(array $emploi)
    {
        if (empty($emploi)){
            throw new ServiceException("Aucune information fournie !");
        }
        $this->emploiRepository->ajouterSansIdEmploi($emploi);
    }

    public function recupererEmplois(): array
    {
        return $this->emploiRepository->recuperer();
    }

    public function supprimerEmploi(int $idEmploi)
    {
        $this->emploiRepository->supprimer($idEmploi);
    }

    /**
     * @throws ServiceException
     */
    public function modifierEmploi(array $emploi)
    {
        $objet = $this->emploiRepository->recupererParClePrimaire($emploi["idEmploi"]);
        if (!isset($objet)) {
            throw new ServiceException("Aucun emploi trouvé pour cet identifiant !");
        }

        $objet->setIdEmploi($emploi["idEmploi"]);
        $objet->setLibEmploi($emploi["libEmploi"]);

        $this->emploiRepository->mettreAJour($objet);
    }
}