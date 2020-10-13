<?php

class Imagen{
    public static function ValidarImagen($imagen){
        $esImagen = false;
        $arrayTipoImg = array('png' => 'image/png','jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg','jpg' => 'image/jpeg',
        'gif' => 'image/gif','bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon','tiff' => 'image/tiff',
        'tif' => 'image/tiff','svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml');
        
        $tipoImg = $imagen['type'];
        
        foreach ($arrayTipoImg as $value) {
            
            if ($value === $tipoImg) {
                $esImagen = true;
                break;
            }
        }

        return $esImagen;
    }
}