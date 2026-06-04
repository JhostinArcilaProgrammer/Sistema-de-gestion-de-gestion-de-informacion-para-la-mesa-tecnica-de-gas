<?php
require_once "Controller.php";
require_once "app/models/JefeFamilia.php";


class JefeFamiliaController extends Controller{


    public function index(){
    	if ($_SESSION['rol_id'] == 3 || $_SESSION['rol_id'] == 2 || $_SESSION['rol_id'] == 1) {
    	$idUsuarioOnline=$_SESSION['usuario_id'];
    	$model= new JefeFamilia;
    	$idJefeCalles=$model->getIdJefeCalle($idUsuarioOnline);

        if ($_SESSION['rol_id'] == 1):
            $familias= $model->getJefesFamiliaByAdmin(); 
        endif;

        if ($_SESSION['rol_id'] == 3):
            $familias= $model->getJefeFamiliaByJefeCalle($idJefeCalles['usuario_id']);
        endif;

        if ($_SESSION['rol_id'] == 2):
            $familias= $model->getJefesFamiliaByJefeSector($idUsuarioOnline);
        endif;


    	
    
        return $this->view("JefeFamilia.index",compact('familias'));
        }else{
            return $this->redirect("index.php?controller=Profile&method=dashboard");
        }
    }

    //formulario para crear un registro
    public function create(){
        if ($_SESSION['rol_id'] == 3 || $_SESSION['rol_id'] == 2 || $_SESSION['rol_id'] == 1) {
            $userId = $_SESSION['usuario_id'];
            $model = new JefeFamilia;

            // Si es Jefe de Calle (rol 3) devolvemos sus datos como antes
            if ($_SESSION['rol_id'] == 3) {
                $idJefeCalles = $model->getIdJefeCalle($userId);
                $JefeCalle = isset($idJefeCalles['id']) ? $idJefeCalles['id'] : null;
                $calle = isset($idJefeCalles['calle']) ? $idJefeCalles['calle'] : null;
                return $this->view('JefeFamilia.create', compact('JefeCalle','calle'));
            }

            // Si es Jefe de Sector (rol 2) traer los jefes de calle pertenecientes a su sector
            if ($_SESSION['rol_id'] == 2) {
                $jefeSector = $model->query("SELECT * FROM jefes_sectores WHERE usuario_id={$userId}")->first();
                $jefesCalles = array();
                if (!empty($jefeSector) && isset($jefeSector['sector_id'])) {
                    $sectorId = $jefeSector['sector_id'];
                    $jefesCalles = $model->query("SELECT jc.id, jc.nombre, c.calle FROM jefes_calles jc INNER JOIN calles c ON jc.calle_id = c.id WHERE c.sector_id = {$sectorId}")->get();
                }
                return $this->view('JefeFamilia.create', compact('jefesCalles'));
            }

            // Rol 1 (admin) u otros: devolver vista con datos por defecto si hace falta
            // Para admin: enviar sectores y jefes de calle (con sectorId) para poblar selectores
            $sectores = $model->getAll('sectores');
            $jefesCalles = $model->query("SELECT jc.id, jc.nombre, c.calle, c.sector_id as sectorId FROM jefes_calles jc INNER JOIN calles c ON jc.calle_id = c.id")->get();
            return $this->view('JefeFamilia.create', compact('sectores','jefesCalles'));

        }else{
            return $this->redirect("index.php?controller=Profile&method=dashboard");
        }
    }

    public function store(){

    	    if ($_SESSION['rol_id'] == 3 || $_SESSION['rol_id'] == 2 || $_SESSION['rol_id'] == 1) {
            $data = $_POST;//datos enviados por el formulario

            // Validación temprana: se debe seleccionar un jefe de calle (clave foránea)
            if (empty($data['jefeCalle'])) {
                $_SESSION['error'] = 'Error: Debe seleccionar la calle responsable (jefe de calle).';
                $_SESSION['sentData'] = $_POST;
                return $this->redirect('index.php?controller=jefeFamilia&method=create');
            }
            //validacion de los datos del jefe de familia
    	    	

            $validation[] = $this->preg_matchValidation($this->letterPattern, $data['nombre']);
            $validation[] = $this->preg_matchValidation($this->letterPattern, $data['apellido']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['documento']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['telefono']);
            $validation[] = $this->preg_matchValidation($this->alphanumericPattern, $data['numeroCasa']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['bombona10']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['bombona18']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['bombona27']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['bombona43']);

            $modelFamilia = new jefeFamilia;//objeto del jefe de familia
            $modelbombona =new inventarioBombona;
            //verificación si hay errores en la validacion y la existencia del registro
            // print_r($_SESSION['error']);
            

            switch(true){

                case (in_array("error", $validation)):
                    $_SESSION['error'] = "Error en el registro. Datos incorrectos, verifique los campos";
                break;

                case($modelFamilia->where('documento','=',$data['documento'])->first()):
                    $_SESSION['error'] = "Error en el registro. La cédula ya registrada, verifica los datos y vuelve a intentar";
                break;

                // case($modelFamilia->where('numero_casa','=',$data['numeroCasa'])->first()):
                //     $_SESSION['error'] = "Error en el registro. El número de casa ya fue registro, verifica los datos y vuelve a intentar";
                // break;


                default:
                    //si no ocurre error, insertar jefe y su usuario

                    //insertar jefeFamilia
                    $dataFamilia = Array();
                    $dataFamilia['jefe_calle_id']=$data['jefeCalle'];
                    $dataFamilia['calle']=$data['calle'];
                    $dataFamilia['nombre']=$data['nombre'];
					$dataFamilia['apellido']=$data['apellido'];
					$dataFamilia['documento']=$data['documento'];
					$dataFamilia['telefono']=$data['telefono'];
					$dataFamilia['email']=$data['email'];
					$dataFamilia['numero_casa']=$data['numeroCasa'];
					$dataFamilia['fecha_registro']=date('Y-m-d');

                    $modelFamilia->create($dataFamilia);

                    //insertar inventario bombonas
                    $dataBombona = Array();
                    
   					$dataBombona['cantidad_bombona_10kg']=$data['bombona10'];
					$dataBombona['cantidad_bombona_18kg']=$data['bombona18'];
					$dataBombona['cantidad_bombona_27kg']=$data['bombona27'];
					$dataBombona['cantidad_bombona_43kg']=$data['bombona43'];
                    $dataBombona['jefe_familia_id'] = $modelFamilia->lastID();
                    // print_r($data);
                    // print_r($dataBombona['jefe_familia_id']);
                    $modelbombona->create($dataBombona);

                    $_SESSION['success'] = "El Jefe de familia".$data['nombre']." ha sido registrado";
                    return $this->redirect('index.php?controller=jefeFamilia&method=index');
            }
            $_SESSION['sentData'] = $_POST;
            $this->redirect('index.php?controller=jefeFamilia&method=create');

        }
        return $this->redirect("index.php?controller=Profile&method=dashboard");
    }

     //formulario para editar un registro
    public function edit(){
        if ($_SESSION['rol_id'] == 3 || $_SESSION['rol_id'] == 2 || $_SESSION['rol_id'] == 1) {
        $id = $_GET['id'];
        $model = new jefeFamilia;
        $familia = $model->getFamilia($id);
        // print_r($familia);
        return $this->view('jefeFamilia.edit', compact('familia'));

        return $this->redirect("index.php?controller=Profile&method=dashboard");
        }

        return $this->redirect("index.php?controller=Profile&method=dashboard");
    }


    //procesar el formulario de editar registro
    public function update(){
        if  ($_SESSION['rol_id'] == 3 || $_SESSION['rol_id'] == 2 || $_SESSION['rol_id'] == 1) {
            // $id = $_GET['id'];//el ID enviado por la URL
            $data = $_POST;//datos enviados por el formulario
            //validacion de los datos de la familia

            $validation[] = $this->preg_matchValidation($this->letterPattern, $data['nombre']);
            $validation[] = $this->preg_matchValidation($this->letterPattern, $data['apellido']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['documento']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['telefono']);
            $validation[] = $this->preg_matchValidation($this->alphanumericPattern, $data['numeroCasa']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['bombona10']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['bombona18']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['bombona27']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['bombona43']);
            //objeto familia
            $modelFamilia = new jefeFamilia;
            $modelBombona =new inventarioBombona;
           
            $familia = $modelFamilia->where('documento','=',$data['documento'])->first();
            $head = $modelFamilia->find($data['id']);

            //verificar validaciones y existencia del registro
            switch(true){
                case(in_array("error", $validation)):
                    $_SESSION['error'] = "Error en la actualización. Datos incorrectos, verifique los campos";
                break;
                case(($familia) && ($familia['documento'] != $data['documento'])):
                    $_SESSION['error'] = "Error. La cédula ya se encuentra asignada";
    
                break;
       
                default:
                    //si no hay ningun error, se actualiza el registro

                    //insertar jefeFamilia
                    $dataFamilia = Array();
                    $dataFamilia['nombre']=$data['nombre'];
					$dataFamilia['apellido']=$data['apellido'];
					$dataFamilia['documento']=$data['documento'];
					$dataFamilia['telefono']=$data['telefono'];
					$dataFamilia['email']=$data['email'];
					$dataFamilia['numero_casa']=$data['numeroCasa'];
					$dataFamilia['fecha_registro']=date('Y-m-d');

                    $modelFamilia->update($head['id'], $dataFamilia);
                    //insertar bombona
                    $dataBombona1 = Array();
                    $dataBombona1['cantidad_bombona_10kg']=$data['bombona10'];
                    $dataBombona1['cantidad_bombona_18kg']=$data['bombona18'];
                    $dataBombona1['cantidad_bombona_27kg']=$data['bombona27'];
                    $dataBombona1['cantidad_bombona_43kg']=$data['bombona43'];
        

                    $modelBombona->updateByForeningKey($head['id'], $dataBombona1, 'jefe_familia_id');

                    $_SESSION['success'] = "Se actualizó el jefe de familia ".$data['nombre'];
                    return $this->redirect("index.php?controller=jefeFamilia&method=index");
            }
            //si ocurre un error se devuelve a la pagina para editar nuevamente
            $_SESSION['sentData'] = $_POST;
            $this->redirect("index.php?controller=jefeFamilia&method=edit&id=".$id);
        }
        return $this->redirect("index.php?controller=jefeFamilia&method=index");
    }
}