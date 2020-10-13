<?php

require_once "./vendor/autoload.php";
use \Firebase\JWT\JWT; //namespace

class Token{
    
    private const KEY = "primerparcial";

    public static function GetToken($user){
        
        $payload = ['dataUser' => $user->email, 'type' => $user->tipo];

        try{
        
            return JWT::encode($payload,Token::KEY);
        
        }catch(Exception $e){
            throw new Exception($e->getMessage);
        }
        

    }

    public static function ValidateToken($token){

        if(empty($token) || $token === ""){
            return null;
        }
        try {

            $user = JWT::decode($token, Token::KEY, array('HS256'));
            
            return $user;

        } catch (\Throwable $th) {

            throw new Exception("Invalid token");
            return null;
        }
        
    }

    public static function GetTipoUser($token,$tipoUser){

        $payload = JWT::decode($token,Token::KEY,array('HS256'));

        if($payload->type == $tipoUser){
            
            return true;
        } else {

            return false;
        }
    }
}