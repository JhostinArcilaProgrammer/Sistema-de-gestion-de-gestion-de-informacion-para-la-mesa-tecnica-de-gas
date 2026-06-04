<?php

require_once "Model.php";

class JefeFamilia extends Model{
	// se utiliza el nombre de la variable protegida para determinar en el model padre que tabla se esta consultado en sus metodos
	protected $table="jefes_Familia";

	public function getIdJefeCalle($id){
		return $this->query("SELECT jefes_calles.usuario_id,jefes_calles.nombre,jefes_calles.id, calles.id as calleId, calles.calle FROM jefes_calles, calles WHERE (jefes_calles.usuario_id=$id) AND (jefes_calles.calle_id=calles.id)")->first();
	}

	public function getFamilia($id){
		return $this->query("SELECT jefes_familia.id, jefes_familia.nombre , jefes_familia.apellido, jefes_familia.documento, jefes_familia.telefono, jefes_familia.email, jefes_familia.numero_casa, jefes_familia.calle, jefes_familia.fecha_registro, jefes_familia.jefe_calle_id, inventario_cilindros.cantidad_bombona_10kg,inventario_cilindros.cantidad_bombona_18kg,inventario_cilindros.cantidad_bombona_27kg,inventario_cilindros.cantidad_bombona_43kg FROM jefes_familia LEFT JOIN inventario_cilindros ON jefes_familia.id = inventario_cilindros.jefe_familia_id WHERE jefes_familia.id= $id")->first();
	}

	public function getJefesFamiliaByAdmin() {
    return $this->query("
		SELECT  
			jefes_familia.id AS familiaId, 
			jefes_familia.nombre, 
			jefes_familia.apellido, 
			jefes_familia.documento, 
			jefes_familia.telefono, 
			jefes_familia.email, 
			jefes_familia.numero_casa, 
			jefes_familia.calle, 
			jefes_familia.fecha_registro, 
			jefes_familia.jefe_calle_id, 
			inventario_cilindros.cantidad_bombona_10kg, 
			inventario_cilindros.cantidad_bombona_18kg, 
			inventario_cilindros.cantidad_bombona_27kg, 
			inventario_cilindros.cantidad_bombona_43kg, 
			jefes_calles.id AS jefeCalleId, 
			jefes_calles.nombre AS jefeCalle, 
			jefes_calles.calle_id, 
			calles.id AS calleId, 
			calles.calle,
			jefes_sectores.id AS jefeSectorId,
			jefes_sectores.nombre AS jefeSector,
			jefes_sectores.apellido AS jefeSectorApellido,
			sectores.id AS sectorId,
			sectores.sector,
			estados_sistema.id
		FROM 
			jefes_familia
		LEFT JOIN
			inventario_cilindros ON inventario_cilindros.jefe_familia_id=jefes_familia.id
		INNER JOIN
			jefes_calles ON jefes_calles.id=jefes_familia.jefe_calle_id
		INNER JOIN 
			calles ON calles.id=jefes_calles.calle_id
		INNER JOIN
			sectores ON sectores.id=calles.sector_id
		INNER JOIN 
			jefes_sectores ON jefes_sectores.sector_id=sectores.id
		INNER JOIN 
			usuarios ON usuarios.id=jefes_sectores.usuario_id
		INNER JOIN
			estados_sistema ON estados_sistema.id=usuarios.estado_sistema_id
		WHERE
			estados_sistema.id=1;
    ")->get();
	}

public function getJefesFamiliaByJefeSector($idJefeSector) {
    return $this->query("
        SELECT 
            jefes_familia.id AS familiaId, 
            jefes_familia.nombre, 
            jefes_familia.apellido, 
            jefes_familia.documento, 
            jefes_familia.telefono, 
            jefes_familia.email, 
            jefes_familia.numero_casa, 
            jefes_familia.calle, 
            jefes_familia.fecha_registro, 
            jefes_familia.jefe_calle_id, 
            inventario_cilindros.cantidad_bombona_10kg, 
            inventario_cilindros.cantidad_bombona_18kg, 
            inventario_cilindros.cantidad_bombona_27kg, 
            inventario_cilindros.cantidad_bombona_43kg, 
            jefes_calles.id AS jefeCalleId, 
            jefes_calles.nombre AS jefeCalle, 
            jefes_calles.calle_id, 
            calles.id AS calleId, 
            calles.calle
		FROM 
			jefes_familia
		LEFT JOIN 
			inventario_cilindros ON jefes_familia.id = inventario_cilindros.jefe_familia_id
		INNER JOIN 
			jefes_calles ON jefes_familia.jefe_calle_id = jefes_calles.id
		INNER JOIN 
			calles ON jefes_calles.calle_id = calles.id
		INNER JOIN 
			sectores ON calles.sector_id = sectores.id
		INNER JOIN 
			jefes_sectores ON sectores.id = jefes_sectores.sector_id
		WHERE 
			jefes_sectores.usuario_id = $idJefeSector
    ")->get();
	}
	public function getJefeFamiliabyJefeCalle($idJefeCalle){
		return $this->query("SELECT jefes_familia.id as familiaId, jefes_familia.nombre , jefes_familia.apellido, jefes_familia.documento, jefes_familia.telefono, jefes_familia.email, jefes_familia.numero_casa, jefes_familia.calle, jefes_familia.fecha_registro, jefes_familia.jefe_calle_id, inventario_cilindros.cantidad_bombona_10kg,inventario_cilindros.cantidad_bombona_18kg,inventario_cilindros.cantidad_bombona_27kg,inventario_cilindros.cantidad_bombona_43kg, jefes_calles.id, jefes_calles.nombre as jefeCalle, jefes_calles.calle_id, calles.id, calles.calle FROM jefes_familia LEFT JOIN inventario_cilindros ON jefes_familia.id = inventario_cilindros.jefe_familia_id INNER JOIN jefes_calles ON jefes_familia.jefe_calle_id = jefes_calles.id INNER JOIN calles ON jefes_calles.calle_id = calles.id WHERE (jefes_calles.usuario_id=$idJefeCalle)")->get();
	}
}


class inventarioBombona extends Model{
	// se utiliza el nombre de la variable protegida para determinar en el model padre que tabla se esta consultado en sus metodos
	protected $table="inventario_cilindros";

}