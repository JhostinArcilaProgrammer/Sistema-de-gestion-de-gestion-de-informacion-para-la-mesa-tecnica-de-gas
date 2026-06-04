<?php

require_once "Model.php";

class JefeCalle extends Model{
	// se utiliza el nombre de la variable protegida para determinar en el model padre que tabla se esta consultado en sus metodos 
    protected $table = "jefes_calles";

    public function getJefe($id){
        return $this->query("SELECT jefes_calles.id as id, jefes_calles.documento, jefes_calles.nombre, jefes_calles.apellido, jefes_calles.apellido, jefes_calles.telefono, jefes_calles.codigo_jefe_Calle, jefes_calles.email, usuarios.usuario, usuarios.clave, roles.rol, estados_sistema.estado_sistema, calles.calle FROM jefes_calles, usuarios, roles, estados_sistema, calles WHERE (jefes_calles.usuario_id=usuarios.id) AND (roles.id = usuarios.rol_id) AND (estados_sistema.id = usuarios.estado_sistema_id) AND (jefes_calles.calle_id=calles.id) AND (jefes_calles.id = ?)",[$id],'i')->first();
    }

    public function getJefes() {
    	return $this->query("SELECT jefes_Calles.id as id, jefes_Calles.documento, jefes_calles.nombre, jefes_calles.apellido, jefes_calles.apellido, jefes_calles.telefono, jefes_calles.codigo_jefe_Calle, jefes_calles.email, usuarios.usuario, roles.rol, estados_sistema.estado_sistema, Calles.calle FROM jefes_calles, usuarios, roles, estados_sistema, calles WHERE (jefes_calles.usuario_id=usuarios.id) AND (roles.id = usuarios.rol_id) AND (estados_sistema.id = usuarios.estado_sistema_id) AND (jefes_calles.calle_id=calles.id)")->get();
    }
}