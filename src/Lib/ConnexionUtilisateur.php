<?php

namespace App\Sensei\Lib;

use App\Sensei\Model\HTTP\Session;
use App\Sensei\Model\Repository\AbstractRepositoryInterface;

/**
 * @name ConnexionUtilisateur
 *
 * @tutorial Classe qui permet de connecter l'utilisateur à l'application
 *
 * @author Hugo Siliveri
 *
 */
class ConnexionUtilisateur implements ConnexionUtilisateurInterface
{
    /**
     * @var string Est une clé qui permet de voir si un utilisateur est déjà connecté dans une session
     */
    private string $cleConnexion = "_utilisateurConnecte";


    public function __construct(private readonly AbstractRepositoryInterface $repository)
    {

    }


    /**
     * Méthode qui va enregistrer l'utilisateur dans une session
     *
     * @param int $idUtilisateur
     * @return void
     */
    public function connecter(int $idUtilisateur): void
    {
        $session = Session::getInstance();
        $session->enregistrer($this->cleConnexion, $idUtilisateur);
    }

    /**
     * Méthode qui va déconnecter l'utilisateur de la session
     *
     * @return void
     */
    public function deconnecter()
    {
        $session = Session::getInstance();
        $session->supprimer($this->cleConnexion);
    }

    /**
     * Méthode qui va voir si le <code>$login</code> est un utilisateur
     *
     * @param $id
     * @return bool <code>true</code> si <code>$login</code> est un utilisateur, <code>false</code> sinon.
     */
    public function estUtilisateur($id): bool
    {
        return (ConnexionUtilisateur::estConnecte() &&
            ConnexionUtilisateur::getIdUtilisateurConnecte() == $id
        );
    }

    /**
     * Méthode qui va renvoyer un booléen sur la connexion de l'utilisateur.
     *
     * @return bool <code>true</code> si l'utilisateur est déjà connecté, <code>false</code> sinon
     */
    public function estConnecte(): bool
    {
        $session = Session::getInstance();
        return $session->existeCle($this->cleConnexion);
    }

    /**
     * Méthode qui va retourner le login de l'utilisateur connecté à cette session. Si l'utilisateur n'est pas
     * connecté, il va renvoyer <code>null</code>
     *
     * @return int|null
     */
    public function getIdUtilisateurConnecte(): ?int
    {
        $session = Session::getInstance();
        if ($session->existeCle($this->cleConnexion)) {
            return $session->lire($this->cleConnexion);
        } else
            return null;
    }

}