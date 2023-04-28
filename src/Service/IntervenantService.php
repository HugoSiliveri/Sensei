<?php

namespace App\Sensei\Service;

use App\Sensei\Lib\ConnexionUtilisateurInterface;
use App\Sensei\Model\Repository\IntervenantRepository;

class IntervenantService implements IntervenantServiceInterface
{

    public function __construct(
        private IntervenantRepository $intervenantRepository,
    )
    {
    }

    public function recupererIntervenants(): array{
        return $this->intervenantRepository->recuperer();
    }
}