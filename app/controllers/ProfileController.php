<?php


require_once "Controller.php";
require_once "app/models/Profile.php";


class ProfileController extends Controller{


    public function dashboard(){
        $model = new Profile;
        $user = $model->find($_SESSION['usuario_id']);
        $estadoName = $model->findField('estado_sistema','estados_sistema','id',$user['estado_sistema_id'])->first();
        $rolName = $model->findField('rol','roles','id',$user['rol_id'])->first();
        return $this->view('profile.dashboard', compact('user','rolName','estadoName'));
    }


    public function logout(){
        session_destroy();
        $this->redirect("index.php?controller=Home&method=loginForm");
    }


    public function edit(){
        $model = new Profile;
        $user = $model->find($_SESSION['usuario_id']);
        return $this->view('profile.edit', compact('user'));
    }


    public function update(){
        $id = $_GET['id'];
        $data = $_POST;
        $validation[] = $this->preg_matchValidation($this->passwordPattern, $data['clave']);
        $validation[] = $this->preg_matchValidation($this->usernamePattern, $data['usuario']);
        $model = new Profile;
        $usuario = $model->where('usuario','=',$data['usuario'])->first();
        $user = $model->find($id);
        switch(true){
            case(in_array("error", $validation)):
                $_SESSION['error'] = "Error en la actualización. Datos incorrectos, verifique los campos";
            break;
            case(($usuario) && ($user['usuario'] != $data['usuario'])):
                $_SESSION['error'] = "El nombre de usuario ya se encuentra asignado";
            break;
            default:
                $_SESSION['usuario']= $data['usuario'];
                $model->update($id, $data);
                $_SESSION['success'] = "Se actualizó el usuario ".$data['usuario'];
                return $this->redirect("index.php?controller=Profile&method=dashboard");
        }
        $_SESSION['sentData'] = $_POST;
        $this->redirect("index.php?controller=Profile&method=edit");
    }


}