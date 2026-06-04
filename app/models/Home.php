<?php

require_once "Model.php";

class Home extends Model{

    protected $table = "usuarios";

	public function getUser($nombre){
        return $this->query("SELECT usuarios.id, usuario, usuarios.fecha_registro, roles.rol, usuarios.rol_id, usuarios.estado_sistema_id, clave FROM usuarios, roles, estados_sistema WHERE (usuarios.usuario = '$nombre') AND (roles.id = usuarios.rol_id) AND (estados_sistema.id = usuarios.estado_sistema_id)")->first();
    }
    
}