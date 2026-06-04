<?php

require_once "Controller.php";
require_once "app/models/JefeSector.php";
require_once "app/models/User.php";


class JefeSectorController extends Controller{


    //listado de registros
    public function index(){
        if ($_SESSION['rol_id']==1) {
            $model = new JefeSector;
            $jefes = $model->getJefes();
            $jefeActivo = $model->getJefeActivo();
            return $this->view('JefeSector.index', compact('jefes','jefeActivo'));
        }else{
            return $this->redirect("index.php?controller=Profile&method=dashboard");
        }
    }

    //formulario para crear un registro
    public function create(){
        $model = new JefeSector;
        $jefeActivo = $model->getJefeActivo();
        if (($_SESSION['rol_id']==1)){

            $sectores = $model->getAll('sectores');

            $estados_sistema = $model->getAll('estados_sistema');
            return $this->view('JefeSector.create', compact('estados_sistema','sectores'));
        }
        return $this->redirect("index.php?controller=jefeSector&method=index");
        
    }


    //procesar el formulario de crear registro
    public function store(){
        if ($_SESSION['rol_id']==1) {

            $data = $_POST;//datos enviados por el formulario
            //validacion de los datos del usuario
            $validation[] = $this->preg_matchValidation($this->usernamePattern, $data['usuario']);
            $validation[] = $this->preg_matchValidation($this->passwordPattern, $data['clave']);
            
            //validacion de los datos personales del jefe
            $validation[] = $this->preg_matchValidation($this->letterPattern, $data['nombre']);
            $validation[] = $this->preg_matchValidation($this->letterPattern, $data['apellido']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['documento']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['telefono']);
            $validation[] = $this->emailValidation($data['email']);
            //objeto jefe
            $modelJefe = new JefeSector;//objeto jefe
            // validar que el sector seleccionado exista en la base de datos
            $sectorExists = $modelJefe->query("SELECT id FROM sectores WHERE sector = ?", [$data['sector']])->first();
            if ($sectorExists && isset($sectorExists['id'])) {
                $validation[] = "exito";
            } else {
                $validation[] = "error";
            }
            $modelUser = new User;//objeto usuario
            $modelJefe = new JefeSector;//objeto jefe
            //verificar si hay errores en la validacion y la existencia del registro
            switch(true){
                case (in_array("error", $validation)):
                $_SESSION['error'] = "Error en el registro. Datos incorrectos, verifique los campos";
                    // print_r($validation);
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
                $dataUser['rol_id'] =2;
                $dataUser['fecha_registro'] = date('Y-m-d');

                $modelUser->create($dataUser);

                //insertar jefe
                $dataJefe = Array();
                $dataJefe['nombre'] = $data['nombre'];
                $dataJefe['apellido'] = $data['apellido'];
                $dataJefe['documento'] = $data['documento'];
                $dataJefe['telefono'] = $data['telefono'];
                $dataJefe['email'] = $data['email'];
                $dataJefe['fecha_registro'] = date('Y-m-d');
                $dataJefe['sector_id'] = $modelJefe->searchId('sectores','sector',$data['sector']);
                unset($data['sector']);
                $dataJefe['usuario_id'] = $modelUser->lastID();

                $modelJefe->create($dataJefe);

                // Si el nuevo jefe se crea como activo, deshabilitar a los demás jefes de ese sector
                if ($dataUser['estado_sistema_id'] == 1) {
                    $sectorId = $dataJefe['sector_id'];
                    $newUserId = $dataJefe['usuario_id'];
                    $others = $modelJefe->query("SELECT usuario_id FROM jefes_sectores WHERE sector_id = ? AND usuario_id != ?", [$sectorId, $newUserId])->get();
                    foreach ($others as $o) {
                        $modelUser->query("UPDATE usuarios SET estado_sistema_id = 2 WHERE id = ?", [$o['usuario_id']], 'i');
                    }
                }

                $_SESSION['success'] = "El Jefe ".$data['nombre']." ha sido registrado";
                return $this->redirect('index.php?controller=JefeSector&method=index');

            }
            
            $_SESSION['sentData'] = $_POST;
            $this->redirect('index.php?controller=JefeSector&method=create');
        }
        return $this->redirect("index.php?controller=Profile&method=dashboard");
    }


    //formulario para editar un registro
    public function edit(){
        if ($_SESSION['rol_id']==1) {
            $id = $_GET['id'];
            $model = new JefeSector;
            $jefe = $model->getJefe($id);
            $estados_sistema = $model->getAll('estados_sistema');

            $sectores = $model->getAll('sectores');

            return $this->view('jefeSector.edit', compact('jefe','estados_sistema','sectores'));

            return $this->redirect("index.php?controller=Profile&method=dashboard");
        }
        return $this->redirect("index.php?controller=Profile&method=dashboard");
    }


    //procesar el formulario de editar registro
    public function update(){
        if ($_SESSION['rol_id']==1){
            $id = $_GET['id'];//el ID enviado por la URL
            $data = $_POST;//datos enviados por el formulario
            //validacion de los datos del usuario
            $validation[] = $this->preg_matchValidation($this->usernamePattern, $data['usuario']);
            $validation[] = $this->preg_matchValidation($this->passwordPattern, $data['clave']);
            
            //validacion de los datos personales del jefe
            $validation[] = $this->preg_matchValidation($this->letterPattern, $data['nombre']);
            $validation[] = $this->preg_matchValidation($this->letterPattern, $data['apellido']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['documento']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['telefono']);
            $validation[] = $this->emailValidation($data['email']);
            //objeto jefe
            $modelJefe = new JefeSector;
            // validar que el sector seleccionado exista en la base de datos
            $sectorExists = $modelJefe->query("SELECT id FROM sectores WHERE sector = ?", [$data['sector']])->first();
            if ($sectorExists && isset($sectorExists['id'])) {
                $validation[] = "exito";
            } else {
                $validation[] = "error";
            }
            
            $jefe = $modelJefe->where('documento','=',$data['documento'])->first();
            $head = $modelJefe->find($id);
            //objeto usuario
            $modelUser = new User;
            $usuario = $modelUser->where('usuario','=',$data['usuario'])->first();
            $user = $modelUser->find($head['usuario_id']);
            //verificar validaciones y existencia del registro
            switch(true){
                case(in_array("error", $validation)):
                $_SESSION['error'] = "Error en la actualización. Datos incorrectos, verifique los campos";
                break;
                case(($jefe) && ($head['documento'] != $data['documento'])):
                $_SESSION['error'] = "Error. La cédula ya se encuentra asignada";
                break;
                case(($usuario) && ($user['usuario'] != $data['usuario'])):
                $_SESSION['error'] = "Error. El nombre de usuario ya se encuentra asignado";
                break;
                default:
                    //si no hay ningun error, se actualiza el registro
                
                $dataUser = Array();
                $dataUser['usuario'] = $data['usuario'];
                $dataUser['clave'] = $data['clave'];
                $dataUser['estado_sistema_id'] = $modelUser->searchId('estados_sistema','estado_sistema',$data['estado_sistema']);
                $dataUser['rol_id'] = 2;

                $modelUser->update($user['id'], $dataUser);

                //insertar jefe
                $dataJefe = Array();
                $dataJefe['documento'] = $data['documento'];
                $dataJefe['nombre'] = $data['nombre'];
                $dataJefe['apellido'] = $data['apellido'];
                $dataJefe['telefono'] = $data['telefono'];
                $dataJefe['email'] = $data['email'];
                $dataJefe['usuario_id'] = $user['id'];
                $dataJefe['sector_id'] = $modelJefe->searchId('sectores','sector',$data['sector']);

                $modelJefe->update($id, $dataJefe);
                // Si el jefe queda activo, deshabilitar a los demás jefes del mismo sector
                if ($dataUser['estado_sistema_id'] == 1) {
                    $sectorId = $dataJefe['sector_id'];
                    $currentUserId = $user['id'];
                    $others = $modelJefe->query("SELECT usuario_id FROM jefes_sectores WHERE sector_id = ? AND usuario_id != ?", [$sectorId, $currentUserId])->get();
                    foreach ($others as $o) {
                        $modelUser->query("UPDATE usuarios SET estado_sistema_id = 2 WHERE id = ?", [$o['usuario_id']], 'i');
                    }
                }
                return $this->redirect("index.php?controller=JefeSector&method=index");
            }
            //si ocurre un error se devuelve a la pagina para editar nuevamente
            $_SESSION['sentData'] = $_POST;
            $this->redirect("index.php?controller=JefeSector&method=edit&id=".$id);
        }
        return $this->redirect("index.php?controller=Profile&method=dashboard");
    }

}

