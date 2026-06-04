<?php

class Controller{

    //Regex o valores para las validaciones

    //validar el nombre de usuario: admite letras, numeros y los caracteres especiales guion, guion bajo y punto
    protected $usernamePattern = "/^[a-zA-Z0-9_\-\.]{3,16}$/";
    //validar la clave de usuario: La contraseña debe tener al entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula y al menos una mayúscula
    protected $passwordPattern = "/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/";
    // validar numeros de telefono
    protected $telefonoPattern = "/^(0426|0414|0412|0424){11}$/";
    //validar roles de usuario: Administrador | Docente
    protected $rolesValues = ['1', '2','3'];      
    //validar estados del usuario: Habilitado | Inhabilitado
    protected $statusValues = ['Habilitado', 'Inhabilitado'];         
    //validar numeros enteros
    protected $intPattern = "/^[0-9,]+$/";
    //validar numeros DECimales
    protected $intDecimal = "/^[0-9,]+$/";
    //validar solo letras
    protected $letterPattern = "/^[a-zA-ZáéíóúAÉÍÓÚÑñ]+[a-zA-ZáéíóúAÉÍÓÚÑñ ]*$/";
    //validar letras y numeros
    protected $alphanumericPattern = "/^[a-zA-ZáéíóúAÉÍÓÚÑñ0-9\.\:\,\;\!\@\"\#\$\%\&\/\(\)\*\-\+\_\{\}\[\]]+[a-zA-ZáéíóúAÉÍÓÚÑñ0-9\.\:\,\;\!\@\"\#\$\%\&\/\(\)\*\-\+\_\{\}\[\] ]*$/";
    //validar fechas
    protected $datePattern = "/^\d{4}\-\d{1,2}\-\d{1,2}$/";
    //validar genero: Femenino | Masculino
    protected $genderValues = ['Femenino','Masculino'];
    //validar sectores
    protected $sectoresValues = ['La Tigrera'];
    protected $callesValues = ['La Urdaneta','Sucre'];

    //METODOS


    //muestra una vista
    public function view($route, $data = []){
        extract($data);
        $route = str_replace('.','/',$route);
        if(file_exists("resources/views/{$route}.php")){
            ob_start();
            require_once "resources/views/{$route}.php";
            $content = ob_get_clean();
            echo $content;
            return;
        }else{
            return "el archivo no existe";
        }
    }


    //redirigir a una ruta
    public function redirect($route){
        echo "<script> window.location='{$route}'</script>";
    }


    //validar un campo select
    public function selectValidation($allowedValues, $sentValue){
        foreach($allowedValues as $key => $value){
            if($value == $sentValue){
                return "exito";
            }
        }
        return "error";
    }


    //validar un campo que debe coincidir con un patron
    public function preg_matchValidation($pattern, $sentValue){
        if(preg_match($pattern, $sentValue)){
            return "exito";
        }
        return "error";
    }


    //validar correo electronico
    public function emailValidation($sentValue){
        if(filter_var($sentValue, FILTER_VALIDATE_EMAIL)){
            return "exito";
        }
        return "error";
    }


    //devolver la edad 
    function maxBirthdate() {
        $fechaactual = date('Y-m-d');
        $nuevafecha = strtotime ('-18 year' , strtotime($fechaactual));
        $nuevafecha = date ('Y-m-d',$nuevafecha);
        return $nuevafecha;
    }


    //determinar a traves de una fecha si el registro es mayor de edad
    function esMayorDeEdad($fechaNacimiento) {
        $fechaNacimientoTimestamp = strtotime($fechaNacimiento);
        $fechaActualTimestamp = time();
        $segundosDiferencia = $fechaActualTimestamp - $fechaNacimientoTimestamp;
        $años = floor($segundosDiferencia / (365 * 24 * 60 * 60));
        if ($años >= 18) {
            return "exito";
        }
        return "error";
    }


    //verificar si la fecha no es mayor que la actual
    public function lessThanToday($sentValue){
        $currentDate = date('Y-m-d');
        if($sentValue <= $currentDate){
            return "exito";
        }
        return "error";
    }


}