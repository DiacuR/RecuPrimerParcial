<?php

class File{

    public static function NoRepetir($lista,$objeto){
        
        $flagRep = false;

        foreach ($lista as $auto) {
                
            if($auto->_patente === $objeto->_patente){
                
                echo "El auto con la patente $auto->_patente, se repite en la lista a serealizar ";
                $flagRep = true;
            }
        }
        return $flagRep;
    }

    public static function Leer($file,$modo,$listaDeAutos,$separacion){

        $archivo = fopen($file,$modo);

        //$size = filesize($file);

        //$fread = fread($archivo,$size);

        while(!feof($archivo)){

            $linea = fgets($archivo);

            $datos = explode($separacion,$linea);

            if(count($datos) > 3){
            
                $auto = new Auto($datos[0],$datos[1],$datos[2],$datos[3]);

                array_push($listaDeAutos,$auto);
            }
            
        }

        $archivoCerrado = fclose($archivo);

        return $listaDeAutos;
    }


    public static function Escribir($file,$modo,$objeto){

        $archivo = fopen($file,$modo);

        $fwrite = fwrite($archivo,$objeto . PHP_EOL);

        //echo "fwrite: $fwrite";   //Cant de bytes escritos

        $archivoCerrado = fclose($archivo);
    }

    public static function Serializar($ruta,$objeto){
        
        $flagAutoRep = false;
        $listaPrevia = File::Deserealizar($ruta);

        if(!is_null($listaPrevia)){

            foreach ($listaPrevia as $auto) {
                
                if($auto->_patente === $objeto->_patente){
                    
                    echo "El auto con la patente $auto->_patente, se repite en la lista a serealizar ";
                    $flagAutoRep = true;
                }
            }
        }

        if(!$flagAutoRep){

        $archivo = fopen($ruta,"a");

        fwrite($archivo, serialize($objeto).PHP_EOL);

        fclose($archivo);
        }        
    }   


    public static function Deserealizar($ruta){

        $lista = array();

        $archivo = fopen($ruta,"r");
        
        while(!feof($archivo)){

            $objeto = unserialize(fgets($archivo));

            if (is_a($objeto,"Auto")) {
            
                array_push($lista,$objeto);
            }
        }

        fclose($archivo);

        return $lista;
    }

    public static function SaveJSON($ruta,$objeto){

        $archivo = fopen($ruta,"w");

        $retorno = fwrite($archivo, json_encode($objeto,JSON_PRETTY_PRINT));
    
        fclose($archivo);
        
        return $retorno;
    }

    public static function ReadJSON($ruta){
        
        $array = array();

        if(file_exists($ruta)){

            $archivo = fopen($ruta,"r");
            $fSize = filesize($ruta);

            if ($fSize > 0) {
                $fread = fread($archivo,$fSize);
            } else {
                $fread = '{}';
            }
            
            $array = json_decode($fread);
            
            fclose($archivo);           
        }

        return $array;
    }
}