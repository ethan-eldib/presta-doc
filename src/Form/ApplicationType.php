<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType {

    
    /**
     * Permet d'avoir la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placehorlder
     * @param array $options
     * 
     * @return array
     */
    protected function getConfiguration($label, $placehorlder, $options = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placehorlder' => $placehorlder
            ]
        ], $options);
    }
}

?>