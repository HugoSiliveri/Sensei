<?php

namespace App\Sensei\Service;

use App\Sensei\Model\DataObject\AbstractDataObject;
use App\Sensei\Model\Repository\UniteServiceRepository;
use App\Sensei\Service\Exception\ServiceException;

class UniteServiceService implements UniteServiceServiceInterface
{
    public function __construct(
        private readonly UniteServiceRepository $uniteServiceRepository,
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
    public function recupererParIdUSReferentiel(string $idUSReferentiel): AbstractDataObject
    {
        $uniteService = $this->uniteServiceRepository->recupererParIdUSReferentiel($idUSReferentiel);
        if (!isset($uniteService)){
            throw new ServiceException("L'unité ne dispose pas d'identifiant référentiel !");
        }
        return $uniteService;
    }

    /**
     * @throws ServiceException
     */
    public function rechercherUniteService(string $recherche): AbstractDataObject
    {
        if ($recherche == "" || count(explode(" ", $recherche)) < 1) {
            throw new ServiceException("La recherche est incomplète !");
        }
        $tab = explode(" ", $recherche);
        $id = (int)$tab[0];

        $uniteService = $this->uniteServiceRepository->recupererParClePrimaire($id);

        if (!isset($uniteService)) {
            throw new ServiceException("L'identifiant est incorrect !");
        }
        return $uniteService;
    }

    /**
     * @throws ServiceException
     */
    public function creerUniteService(array $uniteService)
    {
        if ($uniteService["anneeOuverture"] > $uniteService["anneeCloture"]) {
            throw new ServiceException("L'année d'ouverture est plus récente que l'année de clôture !");
        }
        $this->uniteServiceRepository->ajouterSansIdUniteService($uniteService);
    }

    /**
     * @throws ServiceException
     */
    public function recupererDernierUniteService(): AbstractDataObject
    {
        $result = $this->uniteServiceRepository->recupererDernierElement();
        if (!isset($result)) {
            throw new ServiceException("La table est vide !");
        }
        return $result;
    }

    public function recupererParAnneeOuverture(int $annee): array
    {
        return $this->uniteServiceRepository->recupererParAnneeOuverture($annee);
    }

    /**
     * @throws ServiceException
     */
    public function modifierUniteService(array $uniteService)
    {
        $objet = $this->uniteServiceRepository->recupererParClePrimaire($uniteService["idUniteService"]);
        if (!isset($objet)) {
            throw new ServiceException("Aucune unité de service trouvée pour cet identifiant !");
        }

        $objet->setIdUniteService($uniteService["idUniteService"]);
        $objet->setIdUSReferentiel($uniteService["idUSReferentiel"]);
        $objet->setLibUS($uniteService["libUS"]);
        $objet->setNature($uniteService["nature"]);
        $objet->setAncetre($uniteService["ancetre"]);
        $objet->setAnneeOuverture($uniteService["anneeOuverture"]);
        $objet->setAnneeCloture($uniteService["anneeCloture"]);
        $objet->setECTS($uniteService["ECTS"]);
        $objet->setHeuresCM($uniteService["heuresCM"]);
        $objet->setHeuresTD($uniteService["heuresTD"]);
        $objet->setHeuresTP($uniteService["heuresTP"]);
        $objet->setHeuresStage($uniteService["heuresStage"]);
        $objet->setHeuresTerrain($uniteService["heuresTerrain"]);
        $objet->setHeuresInnovationPedagogique($uniteService["heuresInnovationPedagogique"]);
        $objet->setSemestre($uniteService["semestre"]);
        $objet->setSaison($uniteService["saison"]);
        $objet->setIdPayeur($uniteService["idPayeur"]);
        $objet->setValidite($uniteService["validite"]);
        $objet->setDeleted($uniteService["deleted"]);

        $this->uniteServiceRepository->mettreAJour($objet);
    }

}