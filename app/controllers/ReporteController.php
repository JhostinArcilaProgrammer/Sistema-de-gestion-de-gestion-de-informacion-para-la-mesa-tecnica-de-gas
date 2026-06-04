<?php

require_once "app/models/Despacho.php";
require_once "app/models/Bombona.php";
require_once "app/models/Pago.php";
require_once "Controller.php";
require_once "app/models/Reporte.php";

class ReporteController extends Controller{

    //listado de registros
    public function index(){
        if (($_SESSION['rol_id'] == 2) || ($_SESSION['rol_id']==1) ) {
            $data = $_POST;
            $filtro = "";
            $fechaInicio = "";
            $fechaFin = "";
            $jefeSector = "";
            $sectorSelected = "";

            if (isset($data['fecha_inicio'])) { $fechaInicio = $data['fecha_inicio']; }
            if (isset($data['fecha_fin'])) { $fechaFin = $data['fecha_fin']; }
            if (isset($data['jefeSector'])) { $jefeSector = $data['jefeSector']; }
            if (isset($data['sector'])) { $sectorSelected = $data['sector']; }

            $model = new Reporte;
            $jefes = $model->getAll('jefes_sectores');
            $sectores = $model->getAll('sectores');

            // Si el usuario es jefe de sector y no presionó filtro, limitar por su sector por defecto
            if (!isset($data['botonfiltro']) && $_SESSION['rol_id'] == 2) {
                $jefeInfo = $model->getIdJefeSectores($_SESSION['user_id']);
                if ($jefeInfo && isset($jefeInfo['sector_id'])) {
                    $filtro = "WHERE js.sector_id = {$jefeInfo['sector_id']}";
                }
            }

            // Si presionó el botón de filtrar, construir filtros según inputs
            if (isset($data['botonfiltro'])) {
                // Si el rol es jefe de sector, forzar su sector
                if ($_SESSION['rol_id'] == 2) {
                    $jefeInfo = $model->getIdJefeSectores($_SESSION['user_id']);
                    if ($jefeInfo && isset($jefeInfo['sector_id'])) { $sectorSelected = $jefeInfo['sector_id']; }
                }

                // Filtrar por sector si admin seleccionó uno y no seleccionó jefe
                if (!empty($sectorSelected) && $sectorSelected !== 'todos' && empty($jefeSector)) {
                    $filtro = ($filtro=="") ? "WHERE js.sector_id = $sectorSelected" : $filtro . " AND js.sector_id = $sectorSelected";
                }

                // Filtrar por jefe si se especificó
                if (!empty($jefeSector) && $jefeSector !== 'todos') {
                    // Si hay sector seleccionado, limitar nombres a ese sector
                    if (!empty($sectorSelected) && $sectorSelected !== 'todos') {
                        $jefesSector = array_filter($jefes, function($j) use ($sectorSelected){ return $j['sector_id'] == $sectorSelected; });
                        $nombresJefes = array_column($jefesSector, 'nombre');
                    } else {
                        $nombresJefes = array_column($jefes, 'nombre');
                    }
                    if (in_array($jefeSector, $nombresJefes)) {
                        $filtro = ($filtro=="") ? "WHERE js.nombre = '$jefeSector'" : $filtro . " AND js.nombre = '$jefeSector'";
                    }
                }

                // Filtrar por rango de fechas
                if (!empty($fechaInicio) || !empty($fechaFin)) {
                    if ($fechaInicio == "" || $fechaFin == "") {
                        $_SESSION['errorFiltro'] = 'Error: Debes seleccionar ambos campos de fecha';
                    } elseif ($fechaFin < $fechaInicio) {
                        $_SESSION['errorFiltro'] = 'Error: La fecha final no puede ser menor que fecha de inicio';
                    } else {
                        $filtro = ($filtro=="") ? "WHERE d.fecha BETWEEN '$fechaInicio' AND '$fechaFin'" : $filtro . " AND d.fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
                    }
                }
            }

            $despachoDatos = $model->getDatosDespachoGeneral($filtro);
            $_SESSION['sentData'] = $_POST;
            return $this->view('reporte.index', compact('despachoDatos', 'jefes','sectores'));
        }
        return $this->redirect("index.php?controller=Profile&method=dashboard");
    }
    
    public function show(){
        if (($_SESSION['rol_id'] == 2) || ($_SESSION['rol_id']==1) ) {
            if (isset($_POST['despacho_id'])) {
                $despachoId = $_POST['despacho_id'];

                $data=$_POST;
                $despachoId = $_POST['despacho_id'];
              
                $jefeCalle;
                $tipoBombona;
                $filtro="";
                $model= new reporte;

                if (isset($data['jefeCalle'])) {
                    $jefeCalle = $data['jefeCalle'];
                }

                if (isset($data['tipoBombona'])) {
                    $tipoBombona = $data['tipoBombona'];
                }

                if ($jefeCalle !=="todos") {
                    $filtro.= " AND jc.nombre= '$jefeCalle'"; 
                }

                if ($tipoBombona !=="todos") {
                    $filtro.= " AND b.tamanno='$tipoBombona'"; 
                }


                $datos=$model->getDatosDespachoEspecifico($despachoId, $filtro);
                $jefes=$model->getAll('jefes_calles');
                $bombonas=$model->getAll('bombonas');
                    
                return $this->view('reporte.show', compact('despachoId','datos','jefes','bombonas'));
            }else{

            $despachoId = $_GET['id'];

                
                $filtro="";
                $model= new reporte;

                $datos=$model->getDatosDespachoEspecifico($despachoId, $filtro);
                $jefes=$model->getAll('jefes_calles');
                $bombonas=$model->getAll('bombonas');
                    
                return $this->view('reporte.show', compact('despachoId','datos','jefes','bombonas'));
            }
        }
        return $this->redirect("index.php?controller=Profile&method=dashboard");
    }

}