<?php
/**
 * Función que gestiona que acción debe ejecutarse
 * @param $controller
 * @param $action
 */
function call($controller, $action){
    require_once('application/controllers/' . $controller . 'controller.php');
    switch($controller){
        case 'index':
            $controller = new IndexController();
            break;

    }
    var_dump($controller);
    var_dump($action);
    $controller->{$action}();
}

$controllers = array(
    'index' => ['index', 'conversation', 'start']
);
if (array_key_exists($controller, $controllers)) {
    if (in_array($action, $controllers[$controller])) {
        $this->call($controller, $action);
    }else{
        $this->call('index', 'error');
    }
}else{
    $this->call('index', 'error');
}
?>