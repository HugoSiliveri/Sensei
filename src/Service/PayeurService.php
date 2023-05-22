<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\PayeurRepository;
use App\Sensei\Service\Exception\ServiceException;

class PayeurService implements PayeurServiceInterface
{
    public function __construct(
        private PayeurRepository $payeurRepository,
    )
    {
    }

    public function recupererPayeurs()
    {
        return $this->payeurRepository->recuperer();
    }

    /**
     * @param int $idPayeur
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idPayeur): AbstractDataObject
    {
        $payeur = $this->payeurRepository->recupererParClePrimaire($idPayeur);
        if (!isset($payeur)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $payeur;
        }
    }

    public function creerPayeur(array $payeur)
    {
        $this->payeurRepository->ajouterSansIdPayeur($payeur);
    }

    public function supprimerPayeur(int $idPayeur)
    {
        $this->payeurRepository->supprimer($idPayeur);
    }

    /**
     * @throws ServiceException
     */
    public function modifierPayeur(array $payeur)
    {
        $objet = $this->payeurRepository->recupererParClePrimaire($payeur["idPayeur"]);
        if (!isset($objet)) {
            throw new ServiceException("Aucun payeur trouvé pour cet identifiant !");
        }

        $objet->setIdPayeur($payeur["idPayeur"]);
        $objet->setLibPayeur($payeur["libPayeur"]);

        $this->payeurRepository->mettreAJour($objet);
    }
}