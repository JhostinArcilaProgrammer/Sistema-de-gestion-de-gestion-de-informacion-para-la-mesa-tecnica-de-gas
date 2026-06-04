<?php

class Model{

    //atributos
    protected $db_host = "localhost";
    protected $db_user = "root";
    protected $db_pass = "";
    protected $db_name = "latigrerabd";
    protected $connection;
    protected $query;
    protected $table;//este atributo se reescribe en cada modelo con el valor correspondiente de su tabla
    protected $sql, $data = [], $params = null;


    //constructor de la clase que llama a al metodo de conexion
    public function __construct(){
        $this->connection();
    }


    //establece la conexion con la base de datos
    public function connection(){
        $this->connection = new mysqli($this->db_host,$this->db_user,$this->db_pass,$this->db_name);
        if($this->connection->connect_error){
            die("Error de conexión: ".$this->connection->connect_error);
        }
    }


    //obtener el ultimo ID insertado
    public function lastID(){
        return $this->connection->insert_id;
    }


    //ejecuta la consulta
    public function query($sql, $data = [], $params = null){
        if($data){
            if($params == null){
                $params = str_repeat('s', count($data));
            }
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param($params,...$data);
            $stmt->execute();
            $this->query = $stmt->get_result();
        }else{
            $this->query = $this->connection->query($sql);
        }
        return $this;
    }


    //devuelve el primer registro de la tabla
    public function first(){
        if(empty($this->query)){
            $this->query($this->sql, $this->data, $this->params);
        }
        return $this->query->fetch_assoc();
    }


    //devuelve todos los registros de la tabla
    public function get(){
        if(empty($this->query)){
            $this->query($this->sql, $this->data, $this->params);
        }
        return $this->query->fetch_all(MYSQLI_ASSOC);
    }


    //CONSULTAS

    
    //SELECT * FROM nombre_tabla
    public function all(){
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        return $this->query($sql)->get();
    }


    //SELECT * FROM nombre_tabla WHERE id = numero_id
    public function find($id){
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->query($sql,[$id],'i')->first();
    }


    //SELECT * FROM nombre_tabla WHERE columna operador 'valor'
    public function where($column, $operator, $value = null){
        //permite recibir dos o tres parametros. De manera, que si no se especifica el operador este sera igual (=)
        if($value == null){
            $value = $operator;
            $operator = '=';
        }
        if(empty($this->sql)){
            $this->sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table} WHERE {$column} {$operator} ?";
            $this->data[] = $value;
        }else{
            $this->sql .= " AND {$column} {$operator} ?";
            $this->data[] = $value;
        }
        return $this;
    }


    //INSERT INTO nombre_tabla (columna1, columna2, columnaN) VALUES ('valor1', 'valor2', 'valorN')
    public function create($data){
        $columns = array_keys($data);
        $columns = implode(', ',$columns);
        $values = array_values($data);
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES (".str_repeat('?, ',count($values)-1)."?)";
        $this->query($sql,$values);
    }


    //UPDATE nombre_tabla SET columna1 = valor1, columna2 = 'valor2', columnaN = 'valorN' WHERE id = numero_id
    public function update($id, $data){
        $fields = [];
        foreach($data as $column => $value){
            $fields[] = "{$column} = ?";
        }
        $fields = implode(', ', $fields);
        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = ?";
        $values = array_values($data);
        $values[] = $id;
        $this->query($sql, $values);
        return $this->find($id);
    }

    //Objeto especifico para actulizar las bombonas del invenario
        public function updateByForeningKey($id, $data, $foreingKey){
        $fields = [];
        foreach($data as $column => $value){
            $fields[] = "{$column} = ?";
        }
        $fields = implode(', ', $fields);
        $sql = "UPDATE {$this->table} SET {$fields} WHERE $foreingKey = ?";
        $values = array_values($data);
        $values[] = $id;
        $this->query($sql, $values);
        return $this->find($id);
    }


    //DELETE FROM nombre_tabla WHERE id = numero_id
    public function delete($id){
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->query($sql,[$id],'i');
    }


    //SELECT * FROM nombre_tabla
    public function getAll($table){
        $sql = "SELECT * FROM {$table}";
        return $this->query($sql)->get();
    }


    //SELECT campo FROM nombre_tabla WHERE columna = valor
    public function findField($field,$table, $column, $value){
        $sql = "SELECT {$field} FROM {$table} WHERE {$column} = ?";
        $this->query($sql,[$value]);
        return $this;
    }


    //buscar id
    public function searchId($table,$column,$value){
        $column_id = $this->findField('id',$table,$column,$value)->first();
        $id = $column_id['id'];
        return $id;
    }


    //obtener el ultimo despacho registrado en la tabla despachos
    public function getUltimoDespacho() {
        return $this->query("SELECT id, precio_caleteros, estado_sistema_id FROM despachos ORDER BY id DESC LIMIT 1")->first();
    }

    // obtener el jefe de sector donde que tenga la id de usuario que ingreso
    
    public function getIdJefeSectores($id){
        return $this->query("SELECT * FROM jefes_sectores WHERE (jefes_sectores.usuario_id='$id')")->first();
    }


}