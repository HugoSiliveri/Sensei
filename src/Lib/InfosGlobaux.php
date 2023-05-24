<?php

namespace App\Sensei\Lib;

use App\Sensei\Model\Repository\AbstractRepository;

class InfosGlobaux
{
    private string $depActuel;
    private int $anneeActuelle;

    public function __construct(
        private readonly ConnexionUtilisateur $connexionUtilisateur,
        private readonly AbstractRepository   $serviceAnnuelRepository,
        private readonly AbstractRepository $departementRepository)
    {
        $serviceAnnuel = $this->serviceAnnuelRepository->recupererParIntervenantAnnuelPlusRecent($this->connexionUtilisateur->getIdUtilisateurConnecte());
        $this->depActuel = $this->departementRepository->recupererParClePrimaire($serviceAnnuel->getIdDepartement())->getLibDepartement();
        $this->anneeActuelle = $serviceAnnuel->getMillesime();
    }

    /**
     * @return string
     */
    public function getDepActuel(): string
    {
        return $this->depActuel;
    }

    /**
     * @param string $depActuel
     */
    public function setDepActuel(string $depActuel): void
    {
        $this->depActuel = $depActuel;
    }

    /**
     * @return int
     */
    public function getAnneeActuelle(): int
    {
        return $this->anneeActuelle;
    }

    /**
     * @param int $anneeActuelle
     */
    public function setAnneeActuelle(int $anneeActuelle): void
    {
        $this->anneeActuelle = $anneeActuelle;
    }

}