<?php

require_once "Model.php";

class Reporte extends Model{

    public function getDatosDespachoGeneral($filtro) {
        return $this->query("SELECT
	        	d.id, 
	        	d.fecha, 
	        	js.nombre, 
	        	js.apellido,
	        	js.documento, 
	        	SUM(CASE WHEN b.tamanno = 10 THEN p.cantidad ELSE 0 END) AS total_10kg, 
	        	SUM(CASE WHEN b.tamanno = 18 THEN p.cantidad ELSE 0 END) AS total_18kg, 
	        	SUM(CASE WHEN b.tamanno = 27 THEN p.cantidad ELSE 0 END) AS total_27kg, 
	        	SUM(CASE WHEN b.tamanno = 43 THEN p.cantidad ELSE 0 END) AS total_43kg, 
	        	SUM(p.cantidad) AS total_general 
	    	FROM despachos d 
	    	INNER JOIN pagos p ON p.despacho_id = d.id 
	    	INNER JOIN jefes_sectores js ON d.jefe_sector_id = js.id 
	    	INNER JOIN bombonas b ON p.bombona_id = b.id 
	    		$filtro
	    	GROUP BY 
	    		d.id
	    	")->get();
    }

    public function getDatosDespachoEspecifico($idDespacho, $filtro) {
    return $this->query("SELECT 
	    		d.fecha,
				js.nombre AS JefeSectorNombre,
			    js.apellido AS JefeSectorApellido,
				jc.codigo_jefe_calle, 
			    jc.nombre, 
			    jc.apellido, 
			    c.calle, 
			    b.tamanno,
			    p.precio,
			    p.referencia_pago,
			    SUM(p.cantidad) AS total_cantidad, 
			    SUM(p.cantidad * p.precio) AS pago_total
			FROM jefes_sectores js
			INNER JOIN despachos d ON d.jefe_sector_id=js.id
			INNER JOIN pagos p ON p.despacho_id=d.id 
			INNER JOIN jefes_calles jc ON p.jefe_calle_id = jc.id 
			INNER JOIN calles c ON jc.calle_id = c.id 
			INNER JOIN bombonas b ON p.bombona_id = b.id 
			WHERE d.id=$idDespacho $filtro
			GROUP BY
				jc.codigo_jefe_calle, 
			    jc.nombre, 
			    jc.apellido, 
			    c.calle,
			    b.tamanno
		")->get();
	}
}