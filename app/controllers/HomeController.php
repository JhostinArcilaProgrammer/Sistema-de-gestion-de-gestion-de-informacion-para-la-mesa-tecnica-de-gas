<?php
require_once "Controller.php";
require_once "app/models/Home.php";


class HomeController extends Controller{

    public function loginForm(){
        return $this->view('loginForm');
    }

    public function login(){
        $data = $_POST;
        $validationLogin[] = $this->preg_matchValidation($this->usernamePattern, $data['usuario']);
        
        $validationLogin[] = $this->preg_matchValidation($this->passwordPattern, $data['clave']);
        switch(true){
            case(in_array("error", $validationLogin)):
                $_SESSION['error'] = "El usuario o la contraseña son incorrectos, verifique los campos";
            break;

            default:
                $model = new Home;
                $user = $model->getUser($data['usuario']);
                switch(true){
                    case(($user == false) || ($user['clave'] != $data['clave'])):
                        $_SESSION['error'] = "Error. Verifique su usuario o contraseña";
                    break;
                    case($user["estado_sistema_id"] != 1):
                        $_SESSION["error"] = "Error: Este usuario se encuentra inhabilitado";
                    break;
                    default:
                        // se crea una variable global con el valor header2 para identificar el uso de un segundo header para cambiar los estilos en la parte interna del sistema al usar una validacion.
                        $_SESSION['header2']="header2";
                        $_SESSION['usuario_id'] = $user['id'];
                        $_SESSION['rol_id'] = $user['rol_id'];
                        $_SESSION['usuario'] = $user['usuario'];
                        $_SESSION['rol'] = $user['rol'];
                        $_SESSION['despacho'] = $model->getUltimoDespacho();
                        $_SESSION['success'] = "Bienvenido ".$user['usuario'];
                        if(!$_SESSION['despacho']){
                            $_SESSION['despacho']['estado_sistema_id'] = 2;
                        }
                        return $this->redirect('index.php?controller=Profile&method=dashboard');
                }
        }
        $_SESSION['sentData'] = $_POST;
        return $this->redirect("index.php?controller=Home&method=loginForm");
    }


}