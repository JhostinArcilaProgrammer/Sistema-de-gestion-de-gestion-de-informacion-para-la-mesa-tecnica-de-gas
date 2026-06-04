<?php

require_once "Model.php";

class Bombona extends Model{
	
    protected $table = "bombonas";

    public function getBombonaData(){
        return $this->query("SELECT tamanno, precio FROM bombonas WHERE id = '$id'")->first();
    }
    public function getBombona($id){
        return $this->query("SELECT id, tamanno, precio FROM bombonas WHERE id = '$id'")->first();
    }

    public function getCantidadBombona($id){
        return $this->query("SELECT 
        	bombonas.tamanno, 
        	SUM(pagos.cantidad) AS total_bombonas
        	FROM 
        	pagos, bombonas, despachos	
        	WHERE
        	(despachos.id=pagos.despacho_id) AND (pagos.despacho_id= '$id') AND (bombonas.id=pagos.Bombona_id)
        	")->get();
    }
}