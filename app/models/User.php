<?php

require_once "Model.php";

class User extends Model{

    protected $table = "usuarios";

    public function getUser($id){
        return $this->query("SELECT usuarios.id, nombre_usuario, usuarios.fecha_registro, roles.rol, estados.estado, clave FROM usuarios, roles, estados WHERE (usuarios.usuario = ?) AND (roles.id = usuarios.rol_id) AND (estados.id = usuarios.estado_id)",[$id],'i')->first();
    }

}