<?php

require_once "./File.php";

class Servicio extends File{

    public $_id;
    public $_nombreDelServicio;
    public $_tipo;
    public $_precio;
    public $_demora;

    public function __construct($id,$nombreDelServicio,$tipo,$precio,$demora)
    {
        $this->_id= $id;
        $this->_nombreDelServicio = $nombreDelServicio;
        $this->_tipo = $tipo;
        $this->_precio = $precio;
        $this->_demora = $demora;
    }


    public static function SaveServiciosJSON($listaObj,$pathServiciosJSON){
    
        try {
            
            if(!is_null($listaObj)){

                return parent::SaveJSON($pathServiciosJSON,$listaObj);
            }
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function ReadServiciosJSON($pathServiciosJSON){
        
        try {
                 
            $listaObj = parent::ReadJSON($pathServiciosJSON);
            $arrayServicios = [];
            
            foreach ($listaObj as $dato) {
                $nuevoServicio = new Servicio($dato->_id,$dato->_nombreDelServicio,$dato->_tipo,$dato->_precio,$dato->_demora);
                array_push($arrayServicios,$nuevoServicio);
            }
        
        } catch (\Throwable $e) {

            throw new Exception($e->getMessage());
        }
        
        return $arrayServicios;
    }

}