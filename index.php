<?php
if(isset($_SERVER["REQUEST_URI"])){
    $request = explode('/', $_SERVER["REQUEST_URI"]);
    if(isset($request[2])){
        $controller = $request[1];
        $action = $request[2];
    }else{
        $controller = 'index';
        $action = 'index';
    }
}else{
    $controller = 'index';
    $action = 'index';
}
//carga la vista layout.php
require_once('application/views/layout.php');
?>