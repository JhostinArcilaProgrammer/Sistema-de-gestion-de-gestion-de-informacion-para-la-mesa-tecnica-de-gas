<?php

require_once "Model.php";

class JefeSector extends Model{
	// se utiliza el nombre de la variable protegida para determinar en el model padre que tabla se esta consultado en sus metodos 
    protected $table = "jefes_sectores";

    public function getJefe($id){
        return $this->query("SELECT jefes_sectores.id as id, jefes_sectores.documento, jefes_sectores.nombre, jefes_sectores.apellido, jefes_sectores.apellido, jefes_sectores.telefono, jefes_sectores.email, usuarios.usuario, usuarios.clave, roles.rol, estados_sistema.estado_sistema, sectores.sector FROM jefes_sectores, usuarios, roles, estados_sistema, sectores WHERE (jefes_sectores.usuario_id=usuarios.id) AND (roles.id = usuarios.rol_id) AND (estados_sistema.id = usuarios.estado_sistema_id) AND (jefes_sectores.sector_id=sectores.id) AND (jefes_sectores.id = ?)",[$id],'i')->first();
    }

    public function getJefes() {
    	return $this->query("SELECT jefes_sectores.id as id, jefes_sectores.documento, jefes_sectores.nombre, jefes_sectores.apellido, jefes_sectores.apellido, jefes_sectores.telefono, jefes_sectores.email, usuarios.usuario, roles.rol, estados_sistema.estado_sistema, sectores.sector FROM jefes_sectores, usuarios, roles, estados_sistema, sectores WHERE (jefes_sectores.usuario_id=usuarios.id) AND (roles.id = usuarios.rol_id) AND (estados_sistema.id = usuarios.estado_sistema_id) AND (jefes_sectores.sector_id=sectores.id)")->get();
    }

    public function getJefeActivo() {
        return $this->query("SELECT usuarios.id FROM jefes_sectores, usuarios WHERE ((usuarios.estado_sistema_id = 1) AND (usuarios.id = jefes_sectores.usuario_id))")->first();
    }

}