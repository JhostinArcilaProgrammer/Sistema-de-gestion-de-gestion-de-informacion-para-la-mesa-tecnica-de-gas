<?php

require_once "Controller.php";
require_once "app/models/JefeCalle.php";
require_once "app/models/User.php";

class JefeCalleController extends Controller{

    //listado de registros
    public function index(){
        if(($_SESSION['rol_id'] == 2 || ($_SESSION['rol_id'] == 1 ))){
            $model = new JefeCalle;
            $jefes = $model->getJefes();
            return $this->view('JefeCalle.index', compact('jefes'));
        }return $this->redirect("index.php?controller=Profile&method=dashboard");
    }

    //formulario para crear un registro
    public function create(){
        if(($_SESSION['rol_id'] == 2 || ($_SESSION['rol_id'] == 1 ))){
            $modelUser = new User;
            $model = new JefeCalle;
            $estados_sistema = $modelUser->getAll('estados_sistema');

            // Si es admin: enviar sectores y todas las calles (con sector_id)
            if ($_SESSION['rol_id'] == 1) {
                $sectores = $model->getAll('sectores');
                $calles = $model->query("SELECT id, calle, sector_id FROM calles")->get();
                return $this->view('JefeCalle.create', compact('estados_sistema','sectores','calles'));
            }

            // Si es jefe de sector: obtener su sector y enviar solo las calles de ese sector
            if ($_SESSION['rol_id'] == 2) {
                $userId = $_SESSION['usuario_id'];
                $jefeSector = $model->query("SELECT * FROM jefes_sectores WHERE usuario_id={$userId}")->first();
                $calles = array();
                $sectorId = null;
                if (!empty($jefeSector) && isset($jefeSector['sector_id'])) {
                    $sectorId = $jefeSector['sector_id'];
                    $calles = $model->query("SELECT id, calle FROM calles WHERE sector_id = {$sectorId}")->get();
                }
                return $this->view('JefeCalle.create', compact('estados_sistema','calles','sectorId'));
            }

        }return $this->redirect("index.php?controller=Profile&method=dashboard");
    }

    //procesar el formulario de crear registro
    public function store(){
        if(($_SESSION['rol_id'] == 2 || ($_SESSION['rol_id'] == 1 ))){
        $data = $_POST;//datos enviados por el formulario
        // instanciar modelos necesarios antes de validaciones que los usan
        $modelUser = new User;//objeto usuario
        $modelJefe = new JefeCalle;//objeto jefe
        //validacion de los datos del usuario
        $validation[] = $this->preg_matchValidation($this->intPattern, $data['codigo']);
        $validation[] = $this->preg_matchValidation($this->usernamePattern, $data['usuario']);
        // la contraseña en edición puede quedar vacía (no se cambia); en store() debe cumplir el patrón
        // la contraseña es opcional en la edición: si está vacía no la validamos ni actualizamos
        $updatePassword = false;
        if (isset($data['clave']) && $data['clave'] !== '') {
            $validation[] = $this->preg_matchValidation($this->passwordPattern, $data['clave']);
            $updatePassword = true;
        }

        //validacion de los datos personales del jefe
        $validation[] = $this->preg_matchValidation($this->letterPattern, $data['nombre']);
        $validation[] = $this->preg_matchValidation($this->letterPattern, $data['apellido']);
        $validation[] = $this->preg_matchValidation($this->intPattern, $data['documento']);
        $validation[] = $this->preg_matchValidation($this->intPattern, $data['telefono']);
        $validation[] = $this->emailValidation($data['email']);
        // validar que la calle seleccionada exista en la base de datos
        if (!isset($data['calle']) || empty($data['calle'])) {
            $calleExists = false;
        } else {
            $calleExists = $modelJefe->query("SELECT id FROM calles WHERE calle = ?", [$data['calle']])->first();
        }
        if ($calleExists && isset($calleExists['id'])) {
            $validation[] = "exito";
        } else {
            $validation[] = "error";
        }
        // $modelUser y $modelJefe ya instanciados arriba
        //verificar si hay errores en la validacion y la existencia del registro
        switch(true){
            case (in_array("error", $validation)):
            $_SESSION['error'] = "Error en el registro. Datos incorrectos, verifique los campos";
            break;
            case($modelUser->where('usuario','=',$data['usuario'])->first()):
            $_SESSION['error'] = "Error en el registro. El nombre de usuario ya existe en la base de datos, intente con otro";
            break;
            case($modelJefe->where('documento','=',$data['documento'])->first()):
            $_SESSION['error'] = "Error en el registro. La cédula ya existe en la base de datos, intente con otra";
            break;
            default:
                //si no ocurre error, insertar jefe y su usuario

                //insertar usuario
            $dataUser = Array();
            $dataUser['usuario'] = $data['usuario'];
            $dataUser['clave'] = $data['clave'];
            $dataUser['estado_sistema_id'] = 1;
            $dataUser['rol_id'] = 3;
            $dataUser['fecha_registro'] = date('Y-m-d');

            $modelUser->create($dataUser);

                //insertar jefe
            $dataJefe = Array();
            $dataJefe['codigo_jefe_Calle'] = $data['codigo'];
            $dataJefe['documento'] = $data['documento'];
            $dataJefe['nombre'] = $data['nombre'];
            $dataJefe['apellido'] = $data['apellido'];
            $dataJefe['telefono'] = $data['telefono'];
            $dataJefe['email'] = $data['email'];

            $dataJefe['calle_id'] = $modelJefe->searchId('calles','calle',$data['calle']);
            unset($data['calle']);
            $dataJefe['usuario_id'] = $modelUser->lastID();

            $modelJefe->create($dataJefe);

            $_SESSION['success'] = "El Jefe ".$data['nombre']." ha sido registrado";
            return $this->redirect('index.php?controller=JefeCalle&method=index');

        }
        $_SESSION['sentData'] = $_POST;
        $this->redirect('index.php?controller=JefeCalle&method=create');

    }return $this->redirect("index.php?controller=Profile&method=dashboard");
}


    //datos enviados a la vista del formulario para editar un registro
public function edit(){
    if(($_SESSION['rol_id'] == 2 || ($_SESSION['rol_id'] == 1 ))){
        $id = $_GET['id'];
        $model = new JefeCalle;
        $jefe = $model->getJefe($id);
        $estados_sistema = $model->getAll('estados_sistema');
        
        // Si es admin: enviar sectores y todas las calles (con sector_id)
        if ($_SESSION['rol_id'] == 1) {
            $sectores = $model->getAll('sectores');
            $calles = $model->query("SELECT id, calle, sector_id FROM calles")->get();
            return $this->view('JefeCalle.edit', compact('jefe','estados_sistema','sectores','calles'));
        }

        // Si es jefe de sector: enviar solo las calles del sector del jefe de sector
        if ($_SESSION['rol_id'] == 2) {
            $userId = $_SESSION['usuario_id'];
            $jefeSector = $model->query("SELECT * FROM jefes_sectores WHERE usuario_id={$userId}")->first();
            $calles = array();
            $sectorId = null;
            if (!empty($jefeSector) && isset($jefeSector['sector_id'])) {
                $sectorId = $jefeSector['sector_id'];
                $calles = $model->query("SELECT id, calle FROM calles WHERE sector_id = {$sectorId}")->get();
            }
            return $this->view('JefeCalle.edit', compact('jefe','estados_sistema','calles','sectorId'));
        }
        
        // fallback
        $calles = $model->getAll('calles');
        return $this->view('JefeCalle.edit', compact('jefe','estados_sistema','calles'));
    }return $this->redirect("index.php?controller=Profile&method=dashboard");
    
}


    //procesar el formulario de editar registro
public function update(){
    if(($_SESSION['rol_id'] == 2 || ($_SESSION['rol_id'] == 1 ))){
        $id = $_GET['id'];//el ID enviado por la URL
        $data = $_POST;//datos enviados por el formulario
        // instanciar modelos necesarios antes de validaciones que los usan
        $modelUser = new User;
        $modelJefe = new JefeCalle;
        // validacion de los datos del usuario
        $validation[] = $this->preg_matchValidation($this->intPattern, $data['codigo']);
        $validation[] = $this->preg_matchValidation($this->usernamePattern, $data['usuario']);
        $validation[] = $this->preg_matchValidation($this->passwordPattern, $data['clave']);
        $validation[] = $this->selectValidation($this->statusValues, $data['estado_sistema']);
        //validacion de los datos personales del jefe
        $validation[] = $this->preg_matchValidation($this->letterPattern, $data['nombre']);
        $validation[] = $this->preg_matchValidation($this->letterPattern, $data['apellido']);
        $validation[] = $this->preg_matchValidation($this->intPattern, $data['documento']);
        $validation[] = $this->preg_matchValidation($this->intPattern, $data['telefono']);
        $validation[] = $this->emailValidation($data['email']);
        // validar que la calle seleccionada exista en la base de datos
        if (!isset($data['calle']) || empty($data['calle'])) {
            $calleExists = false;
        } else {
            $calleExists = $modelJefe->query("SELECT id FROM calles WHERE calle = ?", [$data['calle']])->first();
        }
        if ($calleExists && isset($calleExists['id'])) {
            $validation[] = "exito";
        } else {
            $validation[] = "error";
        }
        //objeto jefe (instanciado más arriba)
        $jefe = $modelJefe->where('documento','=',$data['documento'])->first();
        $head = $modelJefe->find($id);
        //objeto usuario (instanciado más arriba)
        $usuario = $modelUser->where('usuario','=',$data['usuario'])->first();
        $user = $modelUser->find($head['usuario_id']);
        //verificar validaciones y existencia del registro
        switch(true){
            case(in_array("error", $validation)):
            $_SESSION['error'] = "Error en la actualización. Datos incorrectos, verifique los campos";
            break;
            case(($jefe) && ($head['documento'] != $data['documento'])):
            $_SESSION['error'] = "Error. La cédula ya se encuentra asignada";
                /*echo $id;
                echo $head['documento'];
                echo "<br>";
                echo $data['documento'];*/
                break;
                case(($usuario) && ($user['usuario'] != $data['usuario'])):
                $_SESSION['error'] = "Error. El nombre de usuario ya se encuentra asignado";
                break;
                default:
                //si no hay ningun error, se actualiza el registro

                $dataUser = Array();
                $dataUser['usuario'] = $data['usuario'];
                if ($updatePassword) {
                    $dataUser['clave'] = $data['clave'];
                }
                $dataUser['estado_sistema_id'] = $modelUser->searchId('estados_sistema','estado_sistema',$data['estado_sistema']);
                $dataUser['rol_id'] = 3;

                $modelUser->update($user['id'], $dataUser);

                //insertar jefe
                $dataJefe = Array();
                $dataJefe['codigo_jefe_Calle'] = $data['codigo'];
                $dataJefe['documento'] = $data['documento'];
                $dataJefe['nombre'] = $data['nombre'];
                $dataJefe['apellido'] = $data['apellido'];
                $dataJefe['telefono'] = $data['telefono'];
                $dataJefe['email'] = $data['email'];
                $dataJefe['calle_id'] = $modelJefe->searchId('calles','calle',$data['calle']);

                $modelJefe->update($id, $dataJefe);

                $_SESSION['success'] = "Se actualizó el jefe ".$data['nombre'];
                return $this->redirect("index.php?controller=JefeCalle&method=index");
            }
        //si ocurre un error se devuelve a la pagina para editar nuevamente
            $_SESSION['sentData'] = $_POST;
            $this->redirect("index.php?controller=JefeCalle&method=edit&id=".$id);
        }return $this->redirect("index.php?controller=Profile&method=dashboard");
    }


}