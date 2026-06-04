<?php

require_once "Model.php";

class Dashboard extends Model{
	// se utiliza el nombre de la variable protegida para determinar en el model padre que tabla se esta consultado en sus metodos 
 
	protected $table = "despachos";

	// public function getUsuario(){
	// 	return$this->query("")
	// }
    public function getDespacho($id){
        return $this->query("SELECT despachos.id as id, despachos.precio_bombona_18kg, despachos.precio_bombona_22kg, despachos.precio_bombona_36kg, despachos.precio_bombona_48kg, despachos.precio_dolar, despachos.precio_caleteros, jefes_sectores.nombre,  estados_sistema.estado_sistema, sectores.sector FROM despachos, jefes_sectores, estados_sistema, sectores WHERE (estados_sistema.id = despachos.estado_sistema_id) ",[$id],'i')->first();
    }

    public function getDespachos() {
    	return $this->query("SELECT despachos.id as id, despachos.precio_bombona_18kg, despachos.precio_bombona_22kg, despachos.precio_bombona_36kg, despachos.precio_bombona_48kg, despachos.precio_dolar, despachos.precio_caleteros, jefes_sectores.nombre,  estados_sistema.estado_sistema, sectores.sector, despachos.fecha_despacho, jefes_sectores.apellido FROM despachos, jefes_sectores, estados_sistema, sectores WHERE (jefes_sectores.id=despachos.jefe_sector_id) and (estados_sistema.id = despachos.estado_sistema_id) ")->get();
    }
}