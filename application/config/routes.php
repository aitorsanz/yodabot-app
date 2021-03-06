<?php
/**
 * Función que gestiona que acción debe ejecutarse
 * @param $controller
 * @param $action
 */
function call($controller, $action){
    require_once('application/controllers/IndexController.php');
    switch($controller){
        case 'index':
            $controller = new IndexController();
            break;

    }
    $controller->{$action}();
}

$controllers = array(
    'index' => ['index', 'conversation', 'start']
);
if (array_key_exists($controller, $controllers)) {
    if (in_array($action, $controllers[$controller])) {
        call($controller, $action);
    }else{
        call('index', 'error');
    }
}else{
    call('index', 'error');
}
?>