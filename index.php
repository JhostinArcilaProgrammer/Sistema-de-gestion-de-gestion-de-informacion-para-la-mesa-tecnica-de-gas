<?php
    //Condicion encargada de evitar el error de session_start repetida
    if(!isset($_SESSION)){
        session_start();
    }

    //llamado de los controladores
    require_once "app/controllers/HomeController.php";
    require_once "app/controllers/ProfileController.php";
    require_once "app/controllers/JefeSectorController.php";
    require_once "app/controllers/JefeCalleController.php";
    require_once "app/controllers/DespachoController.php";
    require_once "app/controllers/ReporteController.php";
    require_once "app/controllers/PagosController.php";
    require_once "app/controllers/JefeFamiliaController.php";
    //En caso de no enviarse $_GET['controller'], $controllerName esta vacio
    if(isset($_GET['controller'])){
        // se concatena "controller" al valor que tiene la variable $_GET['controller']
        $controllerName = $_GET['controller'].'Controller';
    }else{
        $controllerName = "";
    }

    //si no se envia $_GET['method'], $method esta vacio
    if(isset($_GET['method'])){
        // asignacion del valor asignado a $_GET['method']
        $method = $_GET['method'];
    }else{
        $method = "";
    }

    //extraer los primeros 6 caracteres del method, ya que: me interesa capturarlos cuando hacen la palabra export que genera el PDF / archivo que solo trabaje con codigo PHP
    $subMethod = substr($method,0,6);

    //si el metodo lleva la palabra export, no incluye el archivo .../header.php
    // header es la vista del menu antes de haber ingresado al sistema
    if($subMethod != "export"){
        // header para cambiar los estilos en la parte interna del sistema al usar una validacion.
            if (isset($_SESSION['header2']) && $_SESSION['header2']=='header2') {
                require_once "resources/views/layout/header2.php"; 
            }else{
                require_once "resources/views/layout/header.php"; 
            }
               
    }

    switch(true){
        // en el caso de estar logueado algun usuario
        case (!isset($_SESSION['usuario_id'])):
            switch(true){
                // en caso de que la variable $controllerName sea igual a 'HomeController'
                case($controllerName == 'HomeController'):
                    // iteramos la clase asignada a la variable
                    $controller = new $controllerName;
                    // si el metodo existe en el objeto que creamos sigue...
                    if(method_exists($controller,$method)){
                        // generamos en la pagina el menu de disponible antes de ingresar al sistema
                        require_once "resources/views/layout/menu_home.php";
                        $controller -> $method();
                    }else{
                        require_once "resources/views/layout/menu_home.php";
                    require_once "resources/views/main.php";
                    }
                break;
                // si el valor de $controllerName esta vacio, redirije a la vista main(informacion general) y carga el menu de la parte superior.
                case (empty($controllerName)):
                    require_once "resources/views/layout/menu_home.php";
                    require_once "resources/views/main.php";
                break;
                default:
                    echo "Error 404. Página no encontrada.";
            }
        break;
        default:

            switch(true){
                // en caso de que el valor de la variable sea controllerName o este vacio, redirige al dashboard del perfil general del usuario.
                case (($controllerName == 'HomeController') || (empty($controllerName))):
                    header('Location: index.php?controller=Profile&method=dashboard');
                break;
                default:
                    // si existe el controlador la clase itera la un objeto con el string asigando a la variable $controllerName
                    if(class_exists($controllerName)){
                        $controller = new $controllerName;
                        // si el metodo llamado existe  en el objeto creado continua
                        if(method_exists($controller,$method)){
                            // si export no es el valor de $subMethod requiere ... menu_profile.php
                            if($subMethod != "export"){
                                require_once "resources/views/layout/menu_profile.php";
                            }
                            $controller -> $method($id=null);
                        }else{
                            echo "Error 404. Página no encontrada. metodo";
                        }
                    }else{
                        echo "Error 404. Página no encontrada. controlador ";
                    }
            }
    }

    //si el metodo lleva la palabra export, no incluye el archivo footer
    if($subMethod != "export"){
        require_once "resources/views/layout/footer.php";
    }

    if($_SERVER['REQUEST_METHOD'] != 'POST'){
        //eliminar los mensajes guardados en variables de session para eliminar los errores ya mostrados
        if(isset($_SESSION['error'])){
            unset($_SESSION['error']);
        }

        if(isset($_SESSION['success'])){
            unset($_SESSION['success']);
        }

        if(isset($_SESSION['sentData'])){
            unset($_SESSION['sentData']);
        }
    }