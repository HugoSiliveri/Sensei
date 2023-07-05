<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Service\Exception\ServiceException;

interface DepartementServiceInterface
{
    /**
     * @param int $idDepartement
     * @return AbstractDataObject
     * @throws ServiceException
     */
    public function recupererParIdentifiant(int $idDepartement): AbstractDataObject;

    /**
     * @throws ServiceException
     */
    public function changerEtat(int $idDepartement, int $idEtat);

    /**
     * @throws ServiceException
     */
    public function recupererParLibelle(string $lib);

    /**
     * @throws ServiceException
     */
    public function verifierDroitsPourGestion(int $idIntervenant, int $idDepartement);

    public function recupererDepartements(): array;

    /**
     * @throws ServiceException
     */
    public function modifierDepartement(array $departement);

    public function supprimerDepartement(int $idDepartement);

    /**
     * @throws ServiceException
     */
    public function creerDepartement(array $departement);
}