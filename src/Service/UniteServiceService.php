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
}