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
        private int    $idStatut,
        private int    $idDroit,
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

    /**
     * @param int $idIntervenant
     */
    public function setIdIntervenant(int $idIntervenant): void
    {
        $this->idIntervenant = $idIntervenant;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @param string|null $prenom
     */
    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @param int|null $idStatut
     */
    public function setIdStatut(?int $idStatut): void
    {
        $this->idStatut = $idStatut;
    }

    /**
     * @param int|null $idDroit
     */
    public function setIdDroit(?int $idDroit): void
    {
        $this->idDroit = $idDroit;
    }

    /**
     * @param string|null $emailInstitutionnel
     */
    public function setEmailInstitutionnel(?string $emailInstitutionnel): void
    {
        $this->emailInstitutionnel = $emailInstitutionnel;
    }

    /**
     * @param string|null $emailUsage
     */
    public function setEmailUsage(?string $emailUsage): void
    {
        $this->emailUsage = $emailUsage;
    }

    /**
     * @param string|null $idIntervenantReferentiel
     */
    public function setIdIntervenantReferentiel(?string $idIntervenantReferentiel): void
    {
        $this->idIntervenantReferentiel = $idIntervenantReferentiel;
    }

    /**
     * @param int|null $deleted
     */
    public function setDeleted(?int $deleted): void
    {
        $this->deleted = $deleted;
    }

    /**
     * @inheritDoc
     */
    public function exporterEnFormatRequetePreparee(): array
    {
        return [
            "idIntervenantTag" => $this->idIntervenant,
            "nomTag" => $this->nom,
            "prenomTag" => $this->prenom,
            "idStatutTag" => $this->idStatut,
            "idDroitTag" => $this->idDroit,
            "emailInstitutionnelTag" => $this->emailInstitutionnel,
            "emailUsageTag" => $this->emailUsage,
            "idIntervenantReferentielTag" => $this->idIntervenantReferentiel,
            "deletedTag" => $this->deleted
        ];
    }
}