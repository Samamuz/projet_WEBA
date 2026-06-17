<?php
/*
class SecurityHelper {

    private static string $apiKey = "IDjiaosudh128eudaj8ih";

    public static function isAPIKeyValid(): bool {
        //var_dump(getallheaders());

        // TODO
        /*$header = getallheaders();
        if(!isset($header["Authorization"])){
            return false;
        }
        $authorization = $header["Authorization"];
        $token = substr($authorization, 7);
        return $token === self::$apiKey;
        return true;
    }

    public static function generateAPIAccessError() {
        (new Response(httpCode: 401))->generateResponse();
    }
}
*/
?>

<?php

class SecurityHelper {

    // Clé d'API
    private static string $apiKey = "IDjiaosudh128eudaj8ih";


    // Retourne true si la cle est correcte, false dans tous les autres cas.
    public static function isAPIKeyValid(): bool {

        // Recupere tous les en-tetes HTTP
        $headers = getallheaders();

        // Si l'en-tete "Authorization" n'a pas ete envoye, on refuse l'acces.
        if (!isset($headers["Authorization"])) {
            return false;
        }
     
        $authHeader = $headers["Authorization"];
       
        $valeurAttendue = "Bearer " . self::$apiKey;

        if ($authHeader == $valeurAttendue) {
            return true;
        } else {
            return false;
        }
    }

    public static function generateAPIAccessError() {
        (new Response(httpCode: 401))->generateResponse();
    }
}
?>
