<?php

namespace App\Sensei\Lib;

/**
 * @name ConnexionUtilisateurInterface
 *
 * @tutorial Interface pour la connexion utilisateur.
 * Elle intègre les méthodes pour les différents moyens de connexion
 *
 * @author Hugo Siliveri
 *
 */
interface ConnexionUtilisateurInterface
{
    /**
     * Méthode qui va enregistrer l'utilisateur dans une session
     *
     * @param int $idUtilisateur
     * @return void
     */
    public function connecter(int $idUtilisateur): void;

    /**
     * Méthode qui va renvoyer un booléen sur la connexion de l'utilisateur.
     *
     * @return bool <code>true</code> si l'utilisateur est déjà connecté, <code>false</code> sinon
     */
    public function estConnecte(): bool;

    /**
     * Méthode qui va déconnecter l'utilisateur de la session
     *
     * @return void
     */
    public function deconnecter();

    /**
     * Méthode qui va retourner le login de l'utilisateur connecté à cette session. Si l'utilisateur n'est pas
     * connecté, il va renvoyer <code>null</code>
     *
     * @return int|null
     */
    public function getIdUtilisateurConnecte(): ?int;

    /**
     * Méthode qui va voir si le <code>$login</code> est un utilisateur
     *
     * @param $id
     * @return bool <code>true</code> si <code>$login</code> est un utilisateur, <code>false</code> sinon.
     */
    public function estUtilisateur($id): bool;
}