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
        $yodaBot->iniciarConversacion();
        setcookie('respuestasVacias', 0);
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
                    if(is_null($respuesta)){
                        $respuestasVacias = $_COOKIE['respuestasVacias'];
                        $respuestasVacias++;
                        setcookie('respuestasVacias', $respuestasVacias);
                        if($respuestasVacias < 2){
                            $html .= "<p>Yoda: No Response</p></div>";
                        }else{
                            $planetas = $yodaBot->sendRandomYoda();
                            $html .= "<p>Yoda: Films: ";
                            foreach ($planetas as $planeta){
                                $html .= " ".$planeta->name.", ";
                            }
                            $html .= "</p></div>";
                        }
                    }else{
                        $html .= "<p>Yoda: ".$respuesta."</p></div>";
                    }
                }else{
                    $peliculas = $yodaBot->sendForceYoda();
                    $html .= "<p>Yoda: Films: ";
                    foreach ($peliculas as $pelicula){
                        $html .= " ".$pelicula->title.", ";
                    }
                    $html .= "</p></div>";
                }
                echo $html;
                //var_dump($html);
                //echo json_encode(array('estado' => 'ok', 'html' => $html));
                //exit();
            } else {
                echo 'error';
            }
        }


    }

}