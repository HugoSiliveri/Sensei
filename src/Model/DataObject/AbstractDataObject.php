<?php

namespace App\Sensei\Model\DataObject;

/**
 * @name AbstractDataObject
 *
 * @tutorial Classe abstraite destinée à implémenter des méthodes récurrentes dans les DataObject
 *
 * @author Hugo Siliveri
 *
 */
abstract class AbstractDataObject
{
    /**
     * Cette méthode doit être obligatoirement redéfinie dans les sous-classes de AbstractDataObject.
     * Retourne l'objet sous forme de tableau (à utiliser pour les requêtes préparées)
     * @return array
     */
    public abstract function exporterEnFormatRequetePreparee(): array;
}