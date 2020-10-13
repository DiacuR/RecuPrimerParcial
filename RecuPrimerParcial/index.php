<?php

require_once "./Auto.php";
require_once "./Usuario.php";
require_once "./File.php";
require_once "./Token.php";
require_once "./Servicio.php";



use \Firebase\JWT\JWT; //namespace

$method = $_SERVER['REQUEST_METHOD'] ?? '';
$path = $_SERVER['PATH_INFO'] ?? '';
$token = $_SERVER['HTTP_TOKEN'] ?? '';

$auxPath = explode("/",$path);


switch('/'. $auxPath[1]){

    case '/registro':
        if($method == 'POST'){
            try {

                $pathUseresJSON = "./JSON Files/users.json";
                $password = $_POST['password'] ?? '';
                $email = $_POST['email'] ?? '';
                $tipoUsuario = $_POST['tipo'] ?? '';
                $imagen = $_FILES['imagen'] ?? '';
                $origen = $_FILES['imagen']["tmp_name"]??'';

                
                if(!empty($email) && !empty($password) && $tipoUsuario){

                    $usuario = new Usuario($password,$email,$tipoUsuario);
                                    
                    if(!$usuario->SubirImagen($origen,"./img/",$imagen)){

                        echo "No se pudo subir la imagen";
                    }
                    
                    $lista = Usuario::ReadUsuarioJSON($pathUseresJSON);
                    
                    if(!$usuario->verificarUsuario($lista)){
                        
                        array_push($lista, $usuario);
                        
                        if(Usuario::SaveUserJSON($lista,$pathUseresJSON)){
                            echo "Se pudo Guardar el usuario";
                        }
                        else{
                            echo "Error al guardar el usuario";
                        }   
                        
                    } 
                    else{
                        echo "</br>El email ".$usuario->email." ya se encuentra registrado";
                    }

                } else {
                    echo "Faltan Parametros";
                }
                
            } catch (Exception $e) {
                echo "Mensaje error: ".$e."\n";
            }

        } elseif($method == 'GET') {


        }
    break;

    case '/login':
        if($method == 'POST'){
            try {
                $pathUseresJSON = "./JSON Files/users.json";
                $email = $_POST['email']??'';
                $password = $_POST['password']??'';
                
                if(!empty($email) && !empty($password)){
                    
                    $usuario = new Usuario($password,$email);

                    $listaUsuario = Usuario::ReadUsuarioJSON($pathUseresJSON);
                    
                    $usr = $usuario->verificarUsuario($listaUsuario,true);

                    if (!is_null($usr)) {
                        
                        $token = Token::getToken($usr)?? '';
                     
                        if(!empty($token)){

                            echo 'Su token es: '.$token;

                        } else {

                            echo 'Verifique los datos';
                        }

                    } else {

                        echo "</br>El usuario/password no es correcto";
                    }

                } else {

                    echo "Faltan Parametros";
                }    
            } catch (Exception $e) {
                echo "Mensaje error: ".$e->getMessage()."\n";
            }

        } 
    break;

    case '/vehiculo':
        try{
            if($method == 'POST'){

                    $pathArchivoAutos = "./JSON Files/vehiculos.json";
                    
                    $patente = $_POST['patente'] ?? '';
                    $marca = $_POST['marca'] ??'';
                    $modelo = $_POST['modelo'] ?? '';
                    $precio = $_POST['precio'] ?? '';
                    

                    if($patente && $marca && $modelo && $precio){
                        
                        $auto = new Auto($patente, $marca , $modelo, $precio);

                        if(Auto::findAutoByPatente($patente) === null){

                            $listaDeAutos = Auto::ReadAutosJSON($pathArchivoAutos);
                            array_push($listaDeAutos,$auto);

                            if(Auto::SaveAutosJSON($listaDeAutos,$pathArchivoAutos)){
                                
                                echo "Se pudo Guardar el archivo";
                            }
                            else{
                                echo "Error al guardar el archivo";
                            }

                        } else {

                            echo "Ya existe un auto con esa patente";
                        }
                    }
                
                    else{
                        echo "Error. En el token";
                    }
                }
            }
            catch(Exception $e){
                echo $e->getMessage();
            }
    break;

    case '/patente':
        try {
            if($method == 'GET'){
                
                $marca = $_GET['marca'] ??'';
                $modelo = $_GET['modelo'] ?? '';
                $patente = $auxPath[2] ?? null;

                if($patente != null){
    
                    echo Auto::FiltrarAutos('patente',$auxPath[2]);                    
                } 
                if ($marca != '') {
                    
                    echo Auto::FiltrarAutos('marca',$marca);                    
                }
                if($modelo != ''){
    
                    echo Auto::FiltrarAutos('modelo',$modelo);                    
                }
            }
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    break;

    case '/servicio':
        try {
            if($method == 'POST'){

                $id = $_POST['id']?? null;
                $nombreDeServ = $_POST['nombreDeServ'] ?? null;
                $tipo = $_POST['tipo'] ?? null;
                $precio = $_POST['precio'] ?? null;
                $demora = $_POST['demora'] ?? null;
                

                if($id && $nombreDeServ && $tipo && $precio && $demora){
                    
                    $servicio = new Servicio($id,$nombreDeServ,$tipo,$precio,$demora);
                    
                    $listaServ = Servicio::ReadServiciosJSON("./JSON Files/tiposServicios.json");

                    array_push($listaServ,$servicio);

                    if(Servicio::SaveServiciosJSON($listaServ,"./JSON Files/tiposServicios.json")){

                        echo "Se guardo el servicio";
                    } else {

                        echo "No se pudo guardar el Servicio";
                    }

                }

            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    break;

    case '/turno':
        try {
            if($method == 'GET'){

                $id = $_GET['idTipo']?? null;
                $patente = $_GET['patente'] ?? null;
                $dia = $_GET['fecha'] ?? null;
                
                if($id && $patente && $dia){
                    
                }

            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    break;
}