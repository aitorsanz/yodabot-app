<?php
class YodaBot {
    CONST URL_TOKEN = "https://api.inbenta.io/v1/auth";
    CONST URL_APIS = "https://api.inbenta.io/v1/apis";
    CONST URL_MESSAGE = "https://api-gce3.inbenta.io/prod/chatbot/v1/conversation";
    CONST URL_SEND_MESSAGE = "https://api-gce3.inbenta.io/prod/chatbot/v1/conversation/message";
    CONST URL_SEND_FORCE = "https://inbenta-graphql-swapi-prod.herokuapp.com/api";
    CONST SECRET = "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJwcm9qZWN0IjoieW9kYV9jaGF0Ym90X2VuIn0.anf_eerFhoNq6J8b36_qbD4VqngX79-yyBKWih_eA1-HyaMe2skiJXkRNpyWxpjmpySYWzPGncwvlwz5ZRE7eg";
    CONST KEY = "nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=";

    /**
     * Obtener token de conexión a la api
     * @return bool|string
     */
    public function conectarYodaBot(){
        //url de la petición
        $url = self::URL_TOKEN;

        //inicializamos el objeto CUrl
        $ch = curl_init($url);

        //el json simulamos una petición de un login
        $jsonData = array(
            'secret' => self::SECRET
        );

        //creamos el json a partir de nuestro arreglo
        $jsonDataEncoded = json_encode($jsonData);

        //Indicamos que nuestra petición sera Post
        curl_setopt($ch, CURLOPT_POST, 1);

        //para que la peticion no imprima el resultado como un echo comun, y podamos manipularlo
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //Adjuntamos el json a nuestra petición
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

        //Agregamos los encabezados del contenido
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'x-inbenta-key: '.self::KEY ));

        //Ejecutamos la petición
        $result = curl_exec($ch);
        $info = json_decode($result);
        //var_dump($result);
        setcookie('token', $info->accessToken);
        setcookie('expiration', $info->expiration);


    }

    public function iniciarConversacion(){
        //Iniciamos conversación
        $token = $_COOKIE['token'];
        //url de la petición
        $url = self::URL_MESSAGE;

        //inicializamos el objeto CUrl
        $ch = curl_init($url);

        //Indicamos que nuestra petición sera Post
        curl_setopt($ch, CURLOPT_POST, 1);

        //para que la peticion no imprima el resultado como un echo comun, y podamos manipularlo
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //Agregamos los encabezados del contenido
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token, 'x-inbenta-key: '.self::KEY ));

        //Ejecutamos la petición
        $result = curl_exec($ch);
        $info = json_decode($result);

        setcookie('sessionId', $info->sessionId);
        setcookie('sessionToken', $info->sessionToken);
    }

    /**
     * Función que devuleve si el token ha expirado
     * @return bool
     */
    public function isTokenExpirated(){
        if(isset($_COOKIE['expiration'])){
            $fechaActual = strtotime(date("d-m-Y H:i:00",time()));
            $fechaExpirated = strtotime(date("d-m-Y H:i:00", $_COOKIE['expiration']));
            if(strtotime($fechaActual) > strtotime($fechaExpirated)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    public function sendRequestYoda($message){
        $token = $_COOKIE['token'];
        $tokenSession = $_COOKIE['sessionToken'];
        //url de la petición
        $url = self::URL_SEND_MESSAGE;

        //inicializamos el objeto CUrl
        $ch = curl_init($url);

        //el json simulamos una petición de un login
        $jsonData = array(
            'message' => $message
        );

        //creamos el json a partir de nuestro arreglo
        $jsonDataEncoded = json_encode($jsonData);

        //Indicamos que nuestra petición sera Post
        curl_setopt($ch, CURLOPT_POST, 1);

        //para que la peticion no imprima el resultado como un echo comun, y podamos manipularlo
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //Adjuntamos el json a nuestra petición
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

        //Agregamos los encabezados del contenido
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$token, 'x-inbenta-key: '.self::KEY , 'x-inbenta-session: Bearer '.$tokenSession));

        //Ejecutamos la petición
        $result = curl_exec($ch);
        $info = json_decode($result);
        if(isset($info->answers[0]->message)){
            return $info->answers[0]->message;
        }else{
            return null;
        }
    }
    public function sendForceYoda(){
        //url de la petición
        $url = self::URL_SEND_FORCE;

        //inicializamos el objeto CUrl
        $ch = curl_init($url);
        $query = "{allFilms(first: 10){films{title}}}";

        //el json simulamos una petición de un login
        $jsonData = array(
            'query' => $query
        );

        //creamos el json a partir de nuestro arreglo
        $jsonDataEncoded = json_encode($jsonData);

        //Indicamos que nuestra petición sera Post
        curl_setopt($ch, CURLOPT_POST, 1);

        //para que la peticion no imprima el resultado como un echo comun, y podamos manipularlo
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //Adjuntamos el json a nuestra petición
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

        //Agregamos los encabezados del contenido
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        //Ejecutamos la petición
        $result = curl_exec($ch);
        $info = json_decode($result);
        return $info->data->allFilms->films;
    }

    public function sendRandomYoda(){
        //url de la petición
        $url = self::URL_SEND_FORCE;

        //inicializamos el objeto CUrl
        $ch = curl_init($url);
        $query = "{allPlanets(first: 10){planets{name}}}";

        //el json simulamos una petición de un login
        $jsonData = array(
            'query' => $query
        );

        //creamos el json a partir de nuestro arreglo
        $jsonDataEncoded = json_encode($jsonData);

        //Indicamos que nuestra petición sera Post
        curl_setopt($ch, CURLOPT_POST, 1);

        //para que la peticion no imprima el resultado como un echo comun, y podamos manipularlo
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //Adjuntamos el json a nuestra petición
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

        //Agregamos los encabezados del contenido
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        //Ejecutamos la petición
        $result = curl_exec($ch);
        $info = json_decode($result);
        return $info->data->allFilms->films;
    }
}
?>