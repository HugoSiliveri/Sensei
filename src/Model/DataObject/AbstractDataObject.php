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
    public abstract function recupererFormatTableau(): array;
}