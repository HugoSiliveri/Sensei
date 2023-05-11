<?php

namespace App\Sensei\Service;

use App\Sensei\Lib\MessageFlash;
use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\IntervenantRepository;
use App\Sensei\Service\Exception\ServiceException;

class IntervenantService implements IntervenantServiceInterface
{

    public function __construct(
        private IntervenantRepository $intervenantRepository,
    )
    {
    }

    public function recupererIntervenants(): array
    {
        return $this->intervenantRepository->recuperer();
    }

    /**
     * @param int $idIntervenant
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idIntervenant): AbstractDataObject
    {
        $intervenant = $this->intervenantRepository->recupererParClePrimaire($idIntervenant);
        if (!isset($intervenant)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $intervenant;
        }
    }

    public function recupererRequeteIntervenant(): array
    {
        $intervenant = $_GET["intervenant"];
        $tab = ["intervenant" => $intervenant];
        return $this->intervenantRepository->recupererPourAutoCompletion($tab);
    }


    /**
     * @throws ServiceException
     */
    public function rechercherIntervenant(string $recherche): AbstractDataObject
    {
        if ($recherche == "" || count(explode(" ", $recherche)) < 1){
            throw new ServiceException("La recherche est incomplète !");
        }
        $tab = explode(" ", $recherche);
        $id = (int) $tab[0];

        $intervenant = $this->intervenantRepository->recupererParClePrimaire($id);

        if (!isset($intervenant)){
            throw new ServiceException("L'identifiant est incorrect !");
        }
        return $intervenant;
    }

    public function creerIntervenant(array $intervenant) {
        $this->intervenantRepository->ajouterSansIdIntervenant($intervenant);
    }
}