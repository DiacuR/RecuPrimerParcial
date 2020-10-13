<?php

require_once "./File.php";

class Auto extends File{

    public $_patente;
    public $_marca;
    public $_modelo;
    public $_precio;
    
    public function __construct($patente, $marca, $modelo, $precio){
        $this->_patente = $patente;
        $this->_marca = $marca;
        $this->_modelo = $modelo;
        $this->_precio = $precio;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    function cmp($a, $b){
        
        if ($a->_fechaDeIngreso === $b->_fechaDeIngreso) {
            return 0;
        }
        return ($a->_fechaDeIngreso < $b->_fechaDeIngreso) ? -1 : 1;
    }


    public static function StringAutosOrdenadosPorFecha()
    {
        $listaAutos = Auto::ReadAutosJSON("./JSON Files/vehiculos.json");

        $retorno = '';
        
        if($listaAutos){

            if(uasort($listaAutos, array('Auto', 'cmp'))){

                foreach ($listaAutos as $Auto) {

                    $retorno = $retorno . $Auto . "<br><br>";
                   
                }
            } else {
                $retorno = 'ERROR. No se pudo ordenar la lista de autos.';
            }
        }


        return $retorno;
    }

    public static function FiltrarAutos($criterio,$condicion)
    {
        echo 'hola';
        $listaAutos = Auto::ReadAutosJSON("./JSON Files/vehiculos.json");

        $retorno = '';
        echo 'hola';
        if($listaAutos){

            $listaDeAutosFiltrada = Auto::buscarAutosOcurrencias($listaAutos,$criterio,$condicion) ?? null;

            if($listaDeAutosFiltrada){

                foreach ($listaDeAutosFiltrada as $Auto) {

                    $retorno = $retorno . $Auto . "<br><br>";
                   
                }
            } else {
                $retorno = 'No existen '. $criterio;
            }
        }

        return $retorno;
    }

    public static function buscarAutosOcurrencias($listaDeAutos,$criterio,$condicion){

        $lista = array();

        if($criterio === 'marca'){
            
            foreach ($listaDeAutos as $Auto) {
                
                if($Auto->_marca == $condicion){

                    array_push($lista,$Auto);
                }
            }

        } elseif($criterio === 'modelo'){

            foreach ($listaDeAutos as $Auto) {
                
                if($Auto->_modelo == $condicion){

                    array_push($lista,$Auto);
                }
            }

        } elseif ($criterio === 'patente') {
            
            foreach ($listaDeAutos as $Auto) {
                
                if($Auto->_patente == $condicion){

                    array_push($lista,$Auto);
                }
            }
        }

        return $lista;

    }

    public function CalcularImporte($horas){

        if($horas < 4){
            $this->_importe = 30;
        } elseif($horas > 4 && $horas < 12) {
            $this->_importe = 60;
        } elseif ($horas > 12) {
            $this->_importe = 100;
        }
    }


    public function __toString(){
        
        $retorno = '';

        $retorno = $retorno . 'Patente: ' . $this->_patente . "<br>";
        $retorno = $retorno . 'Marca: ' . $this->_marca . "<br>";
        $retorno = $retorno . 'Modelo: ' . $this->_modelo . "<br>";
        $retorno = $retorno . 'Precio: ' . $this->_precio . "<br><br>";
        
        $retorno = $retorno . '-----------------------' . "<br>";

        return $retorno;
    }

    public static function findAutoByPatente($patente){

        $listaDeAutos = Auto::ReadAutosJSON("./JSON Files/vehiculos.json");

        if($listaDeAutos){

            foreach ($listaDeAutos as $Auto) {
                
                if($Auto->_patente == $patente){
                    return $Auto;
                }
            }
        }
        return null;
    }

    public static function SaveAutosJSON($listaObj,$pathAutosJSON){
    
        try {
            
            if(!is_null($listaObj)){

                return parent::SaveJSON($pathAutosJSON,$listaObj);
            }
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function ReadAutosJSON($pathAutosJSON){
        
        try {
                 
            $listaObj = parent::ReadJSON($pathAutosJSON);
            $arrayAutos = [];
            
            foreach ($listaObj as $dato) {
                $nuevoAuto = new Auto($dato->_patente,$dato->_marca,$dato->_modelo,$dato->_precio);
                array_push($arrayAutos,$nuevoAuto);
            }
        
        } catch (\Throwable $e) {

            throw new Exception($e->getMessage());
        }
        
        return $arrayAutos;
    }
}
    