<?php
require_once("Views/Response.php");
require_once("Controllers/Controller.php");

class Router {

    public static function route(string $method, ?string $action): Response|null {

        if ($action == null) {
            return new Response(httpCode: 400);
        }

        $controller = new Controller();
        

        $route1 = $this->controller->getCours();
        var_dump($route1);
        // TODO
        /*if ($action['courses']){
            
        }*/
        return null;
    }
}
?>