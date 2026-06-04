<?php
require_once "Controller.php";
require_once "app/models/Pago.php";
require_once "app/models/despacho.php";

class PagosController extends Controller{

    public function update(){
    	if (($_SESSION['rol_id'] == 3) ) {
           $data=$_POST;
           $idJefeCalle=$_SESSION['usuario_id'];

           $model= new pagos;
           $despacho= new despacho;

           $idJefeCalles=$model->getId($idJefeCalle);
           $despachoActivo= $despacho->getUltimoDespacho();

           $bombona10 = $despacho->getBombona(1);
           $bombona18 = $despacho->getBombona(2);
           $bombona22 = $despacho->getBombona(3);
           $bombona43 = $despacho->getBombona(4);
           $cantidadCaleteros = $data['bombonas10'] + $data['bombonas18'] + $data['bombonas22'] + $data['bombonas43'];
           $despachoActivo= $despacho->getUltimoDespacho();

                $validation[] = $this->preg_matchValidation($this->intPattern, $data['bombonas10']);
                $validation[] = $this->preg_matchValidation($this->intPattern, $data['bombonas18']);
                $validation[] = $this->preg_matchValidation($this->intPattern, $data['bombonas22']);
                $validation[] = $this->preg_matchValidation($this->intPattern, $data['bombonas43']);
                $validation[] = $this->preg_matchValidation($this->intPattern, $data['referencia']);

           switch(true){

            case (in_array("error", $validation)):
            $_SESSION['error'] = "Error en el registro. Datos incorrectos, verifique los campos";
            break;
            case($model->where('referencia','=',$data['referencia'])->first()):
            $_SESSION['error'] = "Error en el registro. La referencia bancaria ya esta registrada, verifica los datos y vuelve a intentar";
            break;
            default:
                    //si no ocurre error, insertar jefe y su usuario
                // Insertar pagos para las bombonas
                $dataPago = [
                    'cantidad' => $data['bombonas10'],
                    'precio' => $bombona10['precio'],
                    'referencia_pago' => $data['referencia'],
                    'bombona_id' => $bombona10['id'],
                    'despacho_id' => $despachoActivo['id'],
                    'jefe_calle_id' => $idJefeCalles['id'],
                    'fecha' => date('Y-m-d'),
                    'cantidad_caleteros' => $cantidadCaleteros, // Cantidad calculada de caleteros
                ];
                $model->create($dataPago);

                $dataPago['cantidad'] = $data['bombonas18'];
                $dataPago['precio'] = $bombona18['precio'];
                $dataPago['bombona_id'] = $bombona18['id'];
                $model->create($dataPago);

                $dataPago['cantidad'] = $data['bombonas22'];
                $dataPago['precio'] = $bombona22['precio'];
                $dataPago['bombona_id'] = $bombona22['id'];
                $model->create($dataPago);

                $dataPago['cantidad'] = $data['bombonas43'];
                $dataPago['precio'] = $bombona43['precio'];
                $dataPago['bombona_id'] = $bombona43['id'];
                $model->create($dataPago);

                $_SESSION['success'] = "El pago con referencia bancaria " . $data['referencia'] . " ha sido registrado";
                return $this->redirect('index.php?controller=despacho&method=pago');
        }

        $_SESSION['sentData'] = $_POST;
            return $this->redirect('index.php?controller=despacho&method=pago');


    }
        return $this->redirect("index.php?controller=Profile&method=dashboard");
}
}