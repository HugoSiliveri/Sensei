<?php

namespace App\Sensei\Service;

use App\Sensei\Model\Repository\AppartenirRepository;
use App\Sensei\Service\Exception\ServiceException;

class AppartenirService implements AppartenirServiceInterface
{
    public function __construct(
        private readonly AppartenirRepository $appartenirRepository,
    )
    {
    }

    public function creerAppartenir(array $appartenir)
    {
        $this->appartenirRepository->ajouterSansIdAppartenir($appartenir);
    }

    /**
     * @throws ServiceException
     */
    public function recupererParIdUniteService(int $idUniteService): array
    {
        $appartenir = $this->appartenirRepository->recupererParIdUniteService($idUniteService);
        if (!isset($appartenir)) {
            throw new ServiceException("Aucune affiliation trouvée pour cette unité !");
        } else {
            return $appartenir;
        }
    }
}