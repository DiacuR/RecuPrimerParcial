<?php

require_once "./File.php";

class Turno extends File{

    public $_patente;
    public $_fecha;
    public $_marca;
    public $_precio;
    public $_modelo;
    public $_tipoDeServ;

    public function __construct($patente,$fecha,$marca,$precio,$modelo,$tipoDeServ)
    {
        $this->_patente= $patente;
        $this->_fecha = $fecha;
        $this->_marca = $marca;
        $this->_precio = $precio;
        $this->_modelo = $modelo;
        $this->_tipoDeServ = $tipoDeServ;
    }


    public static function SaveTurnosJSON($listaObj,$pathTurnosJSON){
    
        try {
            
            if(!is_null($listaObj)){

                return parent::SaveJSON($pathTurnosJSON,$listaObj);
            }
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function ReadTurnosJSON($pathTurnosJSON){
        
        try {
                 
            $listaObj = parent::ReadJSON($pathTurnosJSON);
            $arrayTurnos = [];
            
            foreach ($listaObj as $dato) {
                $nuevoTurno = new Turno($dato->_patente,$dato->_fecha,$dato->_marca,$dato->_precio,$dato->$modelo,$dato->_tipoDeServ);
                array_push($arrayTurnos,$nuevoTurno);
            }
        
        } catch (\Throwable $e) {

            throw new Exception($e->getMessage());
        }
        
        return $arrayTurnos;
    }

}