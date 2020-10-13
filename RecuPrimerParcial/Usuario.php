<?php

require_once "./Imagen.php";
require_once "./File.php";

class Usuario extends File{

    public $password;
    public $email;
    public $imagen;
    public $tipo;

    public function __construct($password, $email, $tipoUsuario = null, $imagen = null) {
        $this->password = $password;
        $this->email = $email;
        $this->tipo = $tipoUsuario;
        $this->imagen = $imagen;
    }

    public function verificarUsuario($array = null, $comparePassword = false) {      //Tener 2 funciones o sobrecargarla con otro parametro para que valide tambien la
        $loginUser = null;                                                                 //contraseña para usar en /login
        
        if(!is_null($array)){
            
            foreach ($array as $user) {
               
                if($this->Equals($user)){
                    $loginUser = $user;
                    
                    if($comparePassword && $this->password === $user->password){
                        $loginUser = $user;
                        
                    }
                    else if($comparePassword){
                        $loginUser = null;
                        
                    }
                    break;
                }
            }
        }
        if(empty($array) || $array === null){
            $loginUser = null;
            
        }
        
        return $loginUser;
    }

    public static function findUserByEmail($emailABuscar){

        $listaUsers = Usuario::ReadUsuarioJSON("./JSON Files/users.xxx");

        foreach ($listaUsers as $usuario) {
            
            if($usuario->email === $emailABuscar){
                return $usuario;
            }
        }
        return null;
    }

    public function Equals($user){
        return $user->email === $this->email;
    }

    public function SubirImagen($pathActual,$pathDestino,$imagen){

        $esImg = false;

        if (Imagen::ValidarImagen($imagen)) {

            $extencion = explode(".",$imagen["name"]);
            $destino = $pathDestino . $this->email . "." .$extencion[1];

            $esImg = move_uploaded_file($pathActual,$destino);
            $imagen['tmp_name'] = $destino;
            $imagen["name"] = $this->email;
            $this->imagen = $imagen;
            
        }

        return $esImg;
    }

    public static function SaveUserJSON($listaObj,$pathUsersJSON){
        try {
            if(!is_null($listaObj)){
                
                return parent::SaveJSON($pathUsersJSON,$listaObj);
            }
        
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function ReadUsuarioJSON($pathUsersJSON){
        try {
            
            $listaObj = parent::ReadJSON($pathUsersJSON);
            $arrayUsuario = [];
            
            foreach ($listaObj as $dato) {
                $nuevoUsuario = new Usuario($dato->password,$dato->email,$dato->tipo,$dato->imagen);
                array_push($arrayUsuario,$nuevoUsuario);
            }
            
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
        
        return $arrayUsuario;
    }
}
