<?php

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
        return $token === self::$apiKey;*/
        return true;
    }

    public static function generateAPIAccessError() {
        (new Response(httpCode: 401))->generateResponse();
    }
}

?>