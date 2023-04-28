<?php

namespace App\Sensei\Service;

use App\Sensei\Model\Repository\UniteServiceRepository;

class UniteServiceService implements UniteServiceServiceInterface
{
    public function __construct(
        private UniteServiceRepository $uniteServiceRepository,
    )
    {
    }

    public function recupererUnitesServices(): array{
        return $this->uniteServiceRepository->recuperer();
    }

    public function recupererRequeteUniteService(): array{
        $uniteService = $_GET["uniteService"];
        $tab = [
            "libUS" => $uniteService,
            "idUSReferentiel" => $uniteService
        ];
        return $this->uniteServiceRepository->recupererPourAutoCompletion($tab);
    }
}