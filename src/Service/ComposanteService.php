<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\ComposanteRepository;
use App\Sensei\Service\Exception\ServiceException;

class ComposanteService implements ComposanteServiceInterface
{
    public function __construct(
        private ComposanteRepository $composanteRepository,
    )
    {
    }

    /**
     * @param int $idComposante
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idComposante): AbstractDataObject
    {
        $composante = $this->composanteRepository->recupererParClePrimaire($idComposante);
        if (!isset($composante)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $composante;
        }
    }

    public function creerComposante(array $composante) {
        $this->composanteRepository->ajouterSansIdComposante($composante);
    }

    public function recupererComposantes() {
        return $this->composanteRepository->recuperer();
    }

    public function supprimerComposante(int $idComposante) {
        $this->composanteRepository->supprimer($idComposante);
    }

    /**
     * @throws ServiceException
     */
    public function modifierComposante(array $composante) {
        $objet = $this->composanteRepository->recupererParClePrimaire($composante["idComposante"]);
        if (!isset($objet)){
            throw new ServiceException("Aucune composante trouvée pour cet identifiant !");
        }

        $objet->setIdComposante($composante["idComposante"]);
        $objet->setLibComposante($composante["libComposante"]);
        $objet->setAnneeDeTravail($composante["anneeDeTravail"]);
        $objet->setAnneeDeValidation($composante["anneeDeValidation"]);

        $this->composanteRepository->mettreAJour($objet);
    }
}