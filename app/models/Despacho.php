<?php

require_once "Model.php";

class Despacho extends Model{
	// se utiliza el nombre de la variable protegida para determinar en el model padre que tabla se esta consultado en sus metodos 
    protected $table = "despachos";

    public function getDespachoActivo(){
        return $this->query("SELECT * FROM despachos WHERE estado_sistema_id = '1'")->first();
    }

    public function getDespacho($id){
        return $this->query("SELECT jefes_calles.id as id, jefes_calles.documento, jefes_calles.nombre, jefes_calles.apellido, jefes_calles.apellido, jefes_calles.telefono, jefes_calles.codigo_jefe_Calle, jefes_calles.email, usuarios.usuario, usuarios.clave, roles.rol, estados_sistema.estado_sistema, calles.calle FROM jefes_calles, usuarios, roles, estados_sistema, calles WHERE (jefes_calles.usuario_id=usuarios.id) AND (roles.id = usuarios.rol_id) AND (estados_sistema.id = usuarios.estado_sistema_id) AND (jefes_calles.calle_id=calles.id) AND (jefes_calles.id = ?)",[$id],'i')->first();
    }

    public function getDespachoFact($id){
        return $this->query("SELECT despachos.id, despachos.fecha, despachos.jefe_sector_id, jefes_sectores.nombre, jefes_sectores.apellido FROM despachos, jefes_sectores WHERE despachos.id=$id AND despachos.jefe_sector_id=jefes_sectores.id")->first();
    }
    
    public function getJefes() {
        return $this->query("SELECT jefes_Calles.id as id, jefes_Calles.documento, jefes_calles.nombre, jefes_calles.apellido, jefes_calles.apellido, jefes_calles.telefono, jefes_calles.codigo_jefe_Calle, jefes_calles.email, usuarios.usuario, roles.rol, estados_sistema.estado_sistema, Calles.calle FROM jefes_calles, usuarios, roles, estados_sistema, calles WHERE (jefes_calles.usuario_id=usuarios.id) AND (roles.id = usuarios.rol_id) AND (estados_sistema.id = usuarios.estado_sistema_id) AND (jefes_calles.calle_id=calles.id)")->get();
    }

    public function getBombona($id){
        return $this->query("SELECT id, tamanno, precio FROM bombonas WHERE id = '$id'")->first();
    }

    public function getTodasBombona(){
        return $this->query("SELECT * FROM bombonas")->get();
    }
}