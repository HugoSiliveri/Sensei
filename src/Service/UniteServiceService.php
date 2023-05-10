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
        if (!isset($idUniteService)) {
            throw new ServiceException("L'identifiant n'est pas défini !");
        } else {
            $uniteService = $this->uniteServiceRepository->recupererParClePrimaire($idUniteService);
            if (!isset($uniteService)) {
                throw new ServiceException("L'identifiant est inconnu !");
            } else {
                return $uniteService;
            }
        }
    }
}