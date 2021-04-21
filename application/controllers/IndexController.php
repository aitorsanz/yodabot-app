<?php
require_once('application/api/YodaBot.php');
class IndexController {
    public function __construct(){

    }

    /**
     * Acción principal que permite realizar peticiones al YodaBot
     * @throws Exception
     */
    public function index(){
        require_once('application/views/index/yodabot.php');
    }

    public function start(){
        $yodaBot = new YodaBot();
        $yodaBot->conectarYodaBot();
    }
    public function conversation(){
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $yodaBot = new YodaBot();
            //var_dump($_COOKIE["token"]);
            if (isset($_POST['message']) && $_POST['message']) {
                $message = $_POST['message'];
                $pos = strpos($message, 'force');
                $html = "<div class='conversacion'><p>Me: ".$message."</p>";
                if($pos === false){
                    //Reallizamos petición a API Yoda
                    $respuesta = $yodaBot->sendRequestYoda($message);
                    $html .= "<p>Yoda: ".$respuesta."</p></div>";
                }else{

                }
                //echo $html;
                echo json_encode(array('html' => $html));
                exit();
            } else {
                echo 'error';
            }
        }


    }

}