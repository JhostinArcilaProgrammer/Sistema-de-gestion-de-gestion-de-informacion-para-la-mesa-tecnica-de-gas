<?php
require_once "Controller.php";
require_once "app/models/Despacho.php";
require_once "app/models/Bombona.php";
require_once "app/models/Pago.php";
require_once "resources/pdf/tfpdf.php";

class DespachoController extends Controller{


    //formulario para crear un registro
    public function create(){
        if((($_SESSION['rol_id'] == 2) || ($_SESSION['rol_id'] == 1)) && !($_SESSION['despacho']['estado_sistema_id'] == 1)){
            $model = new Despacho;
            return $this->view('despacho.create');
        }return $this->redirect("index.php?controller=Profile&method=dashboard");
    }

    //procesar el formulario de crear registro
    public function store(){
        if(($_SESSION['rol_id'] == 2)|| ($_SESSION['rol_id'] == 1)){
        $data = $_POST;//datos enviados por el formulario
        //validacion de los datos del usuario
        
        $modelDespacho = new Despacho;
        $modelBombona = new Bombona;
        $usuarioIdSector=$_SESSION['usuario_id'];
        $DataJefeSector=$modelDespacho->getIdJefeSectores($usuarioIdSector);
        //verificar si hay errores en la validacion y la existencia del registro
        $validation[] = $this->preg_matchValidation($this->intPattern, $data['precio10']);
        $validation[] = $this->preg_matchValidation($this->intPattern, $data['precio18']);
        $validation[] = $this->preg_matchValidation($this->intPattern, $data['precio27']);
        $validation[] = $this->preg_matchValidation($this->intPattern, $data['precio43']);
        $validation[] = $this->preg_matchValidation($this->intPattern, $data['precio_caleteros']);

        switch(true){
            case (in_array("error", $validation)):
            $_SESSION['error'] = "Error en el registro. Datos incorrectos, verifique los campos";
            break;
            default:
                //si no ocurre error, insertar 
                //insertar despacho
            $dataDespacho = Array();
            $dataDespacho['fecha'] = date('Y-m-d');
            $dataDespacho['precio_dolar'] = $data['precio_dolar'];
            $dataDespacho['precio_caleteros'] = $data['precio_caleteros'];
            $dataDespacho['jefe_sector_id'] = $DataJefeSector['id'];
            $dataDespacho['estado_sistema_id'] = 1;
            
            $modelDespacho->create($dataDespacho);

            //actualizar bombona
            $dataBombona10['precio'] = $data['precio10'];
            $dataBombona18['precio'] = $data['precio18'];
            $dataBombona27['precio'] = $data['precio27'];
            $dataBombona43['precio'] = $data['precio43'];

            $modelBombona->update(1,$dataBombona10);
            $modelBombona->update(2,$dataBombona18);
            $modelBombona->update(3,$dataBombona27);
            $modelBombona->update(4,$dataBombona43);

            $_SESSION['despacho']['estado_sistema_id'] = 1;

            $_SESSION['success'] = "El despacho se ha registrado";
            return $this->redirect('index.php?controller=Reporte&method=index');
        }
        $_SESSION['sentData'] = $_POST;
        $this->redirect('index.php?controller=Despacho&method=create');
    }return $this->redirect("index.php?controller=Profile&method=dashboard");
}

    //formulario para editar un registro
public function edit(){
    if((($_SESSION['rol_id'] == 2 ) || ($_SESSION['rol_id'] == 1)) && ($_SESSION['despacho']['estado_sistema_id'] == 1)){
        $model = new Despacho;
        $bombona10 = $model->getBombona(1);
        $bombona18 = $model->getBombona(2);
        $bombona27 = $model->getBombona(3);
        $bombona43 = $model->getBombona(4);
        $despacho = $model->getUltimoDespacho();
        return $this->view('despacho.edit', compact('bombona10','bombona18','bombona27','bombona43','despacho'));
    }return $this->redirect("index.php?controller=Profile&method=dashboard");
}


    //procesar el formulario de editar registro
public function update(){
    if(($_SESSION['rol_id'] == 2) || ($_SESSION['rol_id'] == 1)){
            $data = $_POST;//datos enviados por el formulario
            //validacion de los datos del usuario
            
            $modelDespacho = new Despacho;
            $modelBombona = new Bombona;
            $model=new Pagos;
            $idJefeCalle=$_SESSION['usuario_id'];
            $idJefeCalles=$model->getId($idJefeCalle);
            $despachoActivo= $modelDespacho->getUltimoDespacho();            
            
            //verificar si hay errores en la validacion y la existencia del registro
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['precio10']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['precio18']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['precio27']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['precio43']);
            $validation[] = $this->preg_matchValidation($this->intPattern, $data['precio_caleteros']);

            switch(true){

                case (in_array("error", $validation)):
                $_SESSION['error'] = "Error en el registro. Datos incorrectos, verifique los campos";
                break;

                default:
                    //actualizar despacho
                $dataDespacho = Array();

                $dataDespacho['precio_caleteros'] = $data['precio_caleteros'];

                $modelDespacho->update($_SESSION['despacho']['id'],$dataDespacho);

                    //actualizar bombona
                $dataBombona10['precio'] = $data['precio10'];
                $dataBombona18['precio'] = $data['precio18'];
                $dataBombona27['precio'] = $data['precio27'];
                $dataBombona43['precio'] = $data['precio43'];

                $modelBombona->update(1,$dataBombona10);
                $modelBombona->update(2,$dataBombona18);
                $modelBombona->update(3,$dataBombona27);
                $modelBombona->update(4,$dataBombona43);

                $_SESSION['success'] = "El despacho se ha actualizado";
                return $this->redirect('index.php?controller=Despacho&method=edit');
            }
            $_SESSION['sentData'] = $_POST;
            $this->redirect('index.php?controller=Despacho&method=edit');
        }
            return $this->redirect("index.php?controller=Profile&method=dashboard");
    }


    public function cancel() {
        if(($_SESSION['rol_id'] == 2) || ($_SESSION['rol_id'] == 3) || ($_SESSION['rol_id'] == 1)){
            $_SESSION['despacho']['estado_sistema_id'] = 2;
            $data['estado_sistema_id'] = 2;
            $model = new Despacho;
            $model->update($_SESSION['despacho']['id'],$data);
            $_SESSION['success'] = "El despacho fue desactivado";
            $this->redirect('index.php?controller=Despacho&method=create');
        }return $this->redirect("index.php?controller=Profile&method=dashboard");
    }

    public function pago(){
        $idJefeCalle=$_SESSION['usuario_id'];

        $despacho= new despacho;
        $model= new pagos;

        $idJefeCalles=$model->getId($idJefeCalle);
        $datos=$model->getPagos($idJefeCalles['id']);

        $despachoActivo= $despacho->getUltimoDespacho();
        $pagoRealizado=$model->pagoRealizado($despachoActivo['id'], $idJefeCalles['id']);
        $rowPagos=count($pagoRealizado);
        $bombonas=$despacho->getTodasBombona();
        if ($rowPagos > 0) {
            $_SESSION['pagoRealizado']="";
        }
        // print_r($pagoRealizado);
        return $this->view('despacho.pago', compact('datos','bombonas','despachoActivo'));
    }

    public function show() {
        if(!($_SESSION['rol_id'])):
            return $this->redirect("index.php?controller=Profile&method=dashboard");
        endif;
        
        $despachoId = $_GET['id'];
        $model= new pagos;
        $jefeCalleId=$_SESSION['usuario_id'];
        $idJCalles=$model->getId($jefeCalleId);
        //$datos=$model->getDatosPagos($idJCalles['id'],$despachoId);
        
        $datos=$model->getPagosDeJefe($despachoId, $idJCalles['id']);
        $this->view('Despacho.show',(compact('datos')));
    }

public function exportGenerarPDF() {
    if (!isset($_GET['id'])) {
        die("Error: No se proporcionó un ID para generar el PDF.");
    }

    $id = intval($_GET['id']);
    $model = new pagos;
    $jefeCalleId = $_SESSION['usuario_id'];
    $idJCalles = $model->getId($jefeCalleId);
    $datos = $model->getPagosDeJefe($id, $idJCalles['id']);

    if (!$datos) {
        die("No se encontraron datos para el despacho.");
    }

    // Crear el PDF
    $pdf = new TFPDF();
    $pdf->AddPage();

    // Cargar la fuente DejaVu
    $pdf->AddFont('DejaVu', '', 'DejaVuSans.php');
    $pdf->AddFont('DejaVu', 'B', 'DejaVuSans-Bold.php');
    $pdf->SetFont('Arial', '', 12);

    // Encabezado
    $pdf->Image('resources/assets/img/LogoTigrera.png', 10, 1, 30);
    $pdf->Cell(0, 10, utf8_decode('Comprobante de Pago'), 0, 1, 'C');
    $pdf->Cell(0, 10, utf8_decode('Sector Comunitario La Tigrera'), 0, 1, 'C');
    $pdf->Ln(10);

    // Información del jefe de calle
    $pdf->Cell(0, 10, utf8_decode("Jefe de calle: " . $datos[0]["nombre"] . " " . $datos[0]["apellido"]), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Cédula: " . $datos[0]["documento"]), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Fecha de pago: " . $datos[0]["fecha"]), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Número de referencia de pago: " . $datos[0]["referencia_pago"]), 0, 1);
    $pdf->Ln(10);

    // Tabla de datos
    $pdf->SetFont('DejaVu', '', 10);
    $pdf->Cell(45, 10, utf8_decode('Cilindro'), 1);
    $pdf->Cell(45, 10, utf8_decode('Cantidad'), 1);
    $pdf->Cell(45, 10, utf8_decode('Precio Unitario (Bs.)'), 1);
    $pdf->Cell(45, 10, utf8_decode('Subtotal (Bs.)'), 1);
    $pdf->Ln();

    $subtotal = 0;
    $cantidadTotalBombonas = 0;
    foreach ($datos as $dato) {
        $pdf->Cell(45, 10, utf8_decode($dato['tamanno'] . " KG"), 1);
        $pdf->Cell(45, 10, $dato['cantidad'], 1);
        $pdf->Cell(45, 10, $dato['precio'], 1);
        $pdf->Cell(45, 10, $dato['subtotalbs'], 1);
        $pdf->Ln();
        $subtotal += $dato['subtotalbs'];
        $cantidadTotalBombonas += $dato['cantidad'];
    }

    // Calcular el monto a pagar para los caleteros
    $precioPorCaletero = $dato['precio_caleteros']; // Precio por caletero
    $montoCaleteros = $cantidadTotalBombonas * $precioPorCaletero;

    // Mostrar el monto total de las bombonas, el monto para los caleteros y el total general
    $pdf->SetFont('DejaVu', 'B', 10);
    $pdf->Cell(135, 10, utf8_decode('Monto total para las bombonas (Bs.):'), 1);
    $pdf->Cell(45, 10, round($subtotal, 2), 1);
    $pdf->Ln();
    $pdf->Cell(135, 10, utf8_decode('Monto para los caleteros (Bs.):'), 1);
    $pdf->Cell(45, 10, round($montoCaleteros, 2), 1);
    $pdf->Ln();
    $pdf->Cell(135, 10, utf8_decode('Total (Bs.):'), 1);
    $pdf->Cell(45, 10, round($subtotal + $montoCaleteros, 2), 1);

    // Limpia cualquier salida previa
    ob_clean();

    // Salida del PDF
    $pdf->Output('I', 'comprobante_pago.pdf');
    exit;
}
}
