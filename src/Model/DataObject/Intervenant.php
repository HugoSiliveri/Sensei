<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name Intervenant
 *
 * @tutorial Classe qui représente l'objet Intervenant dans la base de données.
 * L'objet Intervenant correspond à une personne qui serait utilisé dans l'application
 * (pas nécessairement un utilisateur).
 *
 * @author Hugo Siliveri
 *
 */
class Intervenant extends AbstractDataObject
{

    public function __construct(
        private int     $idIntervenant,
        private ?string $nom,
        private ?string $prenom,
        private ?int    $idStatut,
        private ?int    $idDroit,
        private ?string $emailInstitutionnel,
        private ?string $emailUsage,
        private ?string $idIntervenantReferentiel,
        private ?int    $deleted
    )
    {
    }

    /**
     * @return int
     */
    public function getIdIntervenant(): int
    {
        return $this->idIntervenant;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @return int|null
     */
    public function getIdStatut(): ?int
    {
        return $this->idStatut;
    }

    /**
     * @return int|null
     */
    public function getIdDroit(): ?int
    {
        return $this->idDroit;
    }

    /**
     * @return string|null
     */
    public function getEmailInstitutionnel(): ?string
    {
        return $this->emailInstitutionnel;
    }

    /**
     * @return string|null
     */
    public function getEmailUsage(): ?string
    {
        return $this->emailUsage;
    }

    /**
     * @return string|null
     */
    public function getIdIntervenantReferentiel(): ?string
    {
        return $this->idIntervenantReferentiel;
    }

    /**
     * @return int|null
     */
    public function getDeleted(): ?int
    {
        return $this->deleted;
    }

    public function recupererFormatTableau(): array
    {
        return [
            "idIntervenant" => $this->idIntervenant,
            "nom" => $this->nom,
            "prenom" => $this->prenom,
            "idStatut" => $this->idStatut,
            "idDroit" => $this->idDroit,
            "emailInstitutionnel" => $this->emailInstitutionnel,
            "emailUsage" => $this->emailUsage,
            "idIntervenantReferentiel" => $this->idIntervenantReferentiel,
            "deleted" => $this->deleted
        ];
    }
}