<?php
require_once("Views/Response.php");
require_once("Controllers/Controller.php");

class Router {

    public static function route(string $method, ?string $action): Response|null {

        if ($action == null) {
            $resultat = "URL invalide";
            return new Response(httpCode: 400, responseString: json_encode($resultat));
        }

        $controller = new Controller();

        // ----- Action : courses -----
        if ($action == "courses") {

            if ($method == "GET") {
                // Si un id est fourni -> details d'un seul cours.
                if (isset($_GET["id"])) {
                    return $controller->getCourseDetails($_GET["id"]);
                }
                // Si withExercises=true -> liste des cours avec leurs exercices.
                if (isset($_GET["withExercises"]) && $_GET["withExercises"] == "true") {
                    return $controller->getCoursesWithExercises();
                }
                // Sinon -> liste simple des cours.
                return $controller->getCourses();
            }

            if ($method == "POST") {
                return $controller->addCourse();
            }

            if ($method == "DELETE") {
                if (isset($_GET["id"])) {
                    return $controller->deleteCourse($_GET["id"]);
                }
                // DELETE sans id : parametre manquant.
                return new Response(httpCode: 400);
            }
        }

        // ----- Action : exercises -----
        if ($action == "exercises") {

            if ($method == "POST") {
                return $controller->addExercise();
            }

            if ($method == "DELETE") {
                if (isset($_GET["id"])) {
                    return $controller->deleteExercise($_GET["id"]);
                }
                return new Response(httpCode: 400);
            }
        }

        // ----- Action : latecourses -----
        if ($action == "latecourses") {
            if ($method == "GET") {
                return $controller->getLateCourses();
            }
        }

        // Aucune correspondance action/methode -> null -> index.php genere un 404.
        return null;
    }
}

?>
