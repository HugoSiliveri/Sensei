<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\UniteServiceRepository;
use App\Sensei\Service\Exception\ServiceException;

class UniteServiceService implements UniteServiceServiceInterface
{
    public function __construct(
        private UniteServiceRepository $uniteServiceRepository,
    )
    {
    }

    public function recupererUnitesServices(): array
    {
        return $this->uniteServiceRepository->recuperer();
    }

    public function recupererRequeteUniteService(): array
    {
        $uniteService = $_GET["uniteService"];
        $tab = ["uniteService" => $uniteService];
        return $this->uniteServiceRepository->recupererPourAutoCompletion($tab);
    }

    /**
     * @param int $idUniteService
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idUniteService): AbstractDataObject
    {
        $uniteService = $this->uniteServiceRepository->recupererParClePrimaire($idUniteService);
        if (!isset($uniteService)) {
            throw new ServiceException("L'identifiant est inconnu !");
        } else {
            return $uniteService;
        }
    }

    /**
     * @throws ServiceException
     */
    public function rechercherUniteService(string $recherche): AbstractDataObject
    {
        if ($recherche == "" || count(explode(" ", $recherche)) < 1){
            throw new ServiceException("La recherche est incomplète !");
        }
        $tab = explode(" ", $recherche);
        $id = (int) $tab[0];

        $uniteService = $this->uniteServiceRepository->recupererParClePrimaire($id);

        if (!isset($uniteService)){
            throw new ServiceException("L'identifiant est incorrect !");
        }
        return $uniteService;
    }
}