<?php

require_once "Model.php";

class Pagos extends Model{

    protected $table = "pagos";
    
	public function getId($id){
		return $this->query("SELECT jefes_calles.usuario_id,jefes_calles.nombre,jefes_calles.id, calles.id as calleId, calles.calle FROM jefes_calles, calles WHERE (jefes_calles.usuario_id=$id) AND (jefes_calles.calle_id=calles.id)")->first();
	}

	public function getCaleteros($despacho){
		return $this->query("SELECT SUM(cantidad) AS totalCaleteros FROM pagos WHERE despacho_id=$despacho")->first();
	}
	
    public function getTodosDespachos($filtro) {
        return $this->query("SELECT despachos.id, despachos.fecha, despachos.precio_dolar, despachos.jefe_sector_id, 
            jefes_sectores.documento, jefes_sectores.apellido, jefes_sectores.email, jefes_sectores.nombre, jefes_sectores.telefono 
        FROM despachos 
        INNER JOIN jefes_sectores ON despachos.jefe_sector_id = jefes_sectores.id $filtro")->get();
    }

	public function getUltimosPagos() {
        return $this->query("SELECT id FROM despachos ORDER BY id DESC LIMIT 1")->first();
    }

    public function getPagos($jefeCalle) {
        return $this->query("SELECT MIN(id) AS id, referencia_pago, fecha, despacho_id FROM pagos WHERE (jefe_calle_id = $jefeCalle) GROUP BY referencia_pago")->get();
    }

    public function getDatosDespacho(){
        return $this->query("SELECT pagos.id, pagos.cantidad, pagos.precio, pagos.fecha, pagos.referencia_pago, pagos.bombona_id, pagos.despacho_id, despachos.precio_dolar, bombonas.tamanno, despachos.id AS IdDespacho,  despachos.fecha AS fechaDespacho, jefes_sectores.nombre AS nombreJSector, jefes_sectores.apellido AS apellidoJSector, despachos.estado_sistema_id FROM pagos, despachos, bombonas, jefes_sectores WHERE (pagos.bombona_id=bombonas.id) AND (despachos.id=pagos.despacho_id) AND (despachos.jefe_sector_id=jefes_sectores.id) ")->get();
    }

        public function getDatosPagos($jefeCalle, $id) {
        return $this->query("SELECT pagos.id, pagos.cantidad, pagos.precio,pagos.fecha, pagos.referencia_pago, pagos.bombona_id, pagos.despacho_id, despachos.precio_dolar, bombonas.tamanno, despachos.id AS IdDespacho, jefes_calles.nombre, jefes_calles.apellido, jefes_calles.documento FROM jefes_calles,pagos, despachos, bombonas WHERE (jefes_calles.id = $jefeCalle) AND (pagos.despacho_id=$id) AND (pagos.bombona_id=bombonas.id)LIMIT 4 ")->get();
    }

    public function pagoRealizado($IdDespacho, $idJefeCalle){
         return $this->query("SELECT * FROM `pagos` WHERE pagos.despacho_id= '$IdDespacho' AND pagos.jefe_calle_id='$idJefeCalle'")->get();
    }
    public function getPagosDeJefe($despachoId, $idJefeCalle){
        return $this->query("SELECT pagos.despacho_id, bombonas.tamanno, pagos.cantidad, pagos.precio, despachos.precio_caleteros AS precio_caleteros, (pagos.cantidad * pagos.precio) AS subtotalbs, jefes_calles.nombre, jefes_calles.apellido, jefes_calles.documento, pagos.fecha, pagos.referencia_pago, (pagos.cantidad_caleteros*(pagos.cantidad * pagos.precio)) AS pagoCaleteros FROM bombonas, despachos, pagos, jefes_calles WHERE ((pagos.bombona_id = bombonas.id) AND (pagos.despacho_id = despachos.id) AND (pagos.despacho_id = '$despachoId') AND (pagos.jefe_calle_id = '$idJefeCalle') AND (jefes_calles.id=pagos.jefe_calle_id))")->get();
    }

    public function getPagosDeTodosJefes($despachoId){
    return $this->query("SELECT * FROM `pagos`,`jefes_calles`, `bombonas`, `calles` WHERE (pagos.despacho_id=33) AND (pagos.jefe_calle_id=jefes_calles.id) AND (bombonas.id=pagos.bombona_id) AND (calles.id=jefes_calles.calle_id) ")->get();   
    }
}