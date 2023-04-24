<?php

namespace App\Sensei\Model\Repository;

use App\Sensei\Model\DataObject\AbstractDataObject;

/**
 * @name AbstractRepositoryInterface
 *
 * @tutorial Interface qui regroupe toutes les méthodes communes aux classes gérant la persistance des données
 * (répertoire Repository).
 *
 * @author Hugo Siliveri
 *
 */
interface AbstractRepositoryInterface
{
    /** Récupère un ou plusieurs tuples d'une table dans la base de données.
     * @param int|string $limit Nombre de réponses ("ALL" pour toutes les réponses)
     * @return AbstractDataObject[] les objets construits à partir des données récupérées
     */
    public function recuperer($limit = 200): array;

    /** Récupère un ou plusieurs tuples d'une table en passant par une condition
     * @param array $critereSelection ex: ["nomColonne" => valeurDeRecherche]
     * @return AbstractDataObject[] les objets construits à partir des données récupérées
     */
    public function recupererPar(array $critereSelection, $limit = 200): array;

    /** Récupère un tuple d'une table à l'aide de sa clé primaire
     * @param string $valeurClePrimaire la clé primaire de la ligne
     * @return AbstractDataObject|null l'objet construit à partir du tuple si la récupération des données a fonctionné, null sinon
     */
    public function recupererParClePrimaire(string $valeurClePrimaire): ?AbstractDataObject;

    /** Supprime un tuple à l'aide de sa clé primaire
     * @param string $valeurClePrimaire la clé primaire du tuple
     * @return bool vrai si la suppression a fonctionné, faux sinon
     */
    public function supprimer(string $valeurClePrimaire): bool;

    /** modifie un tuple d'une table en passant en paramètre l'objet qui lui correspond
     * @param AbstractDataObject $object l'objet à modifier
     * @return void
     */
    public function mettreAJour(AbstractDataObject $object): void;

    /** Insère les données d'un objet dans la base de données
     * @param AbstractDataObject $object l'objet à ajouter
     * @return bool vrai si l'insertion a fonctionné, faux sinon
     */
    public function ajouter(AbstractDataObject $object): bool;

}