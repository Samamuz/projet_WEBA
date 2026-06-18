<?php

include_once("Model/Model.php");
include_once("Views/Response.php");

class Controller {

    private $model;

    public function __construct() {
        $this->model = new Model(withErrors: true);
    }

    // GET ?action=courses -> liste des cours sans exercices.
    public function getCourses() {
        $lignes = $this->model->getListeCours();

        $resultat = array();
        foreach ($lignes as $ligne) {
            $cours = array();
            $cours["id"] = (int) $ligne["id"];
            $cours["name"] = $ligne["name"];
            $cours["deadline"] = $ligne["deadline"]; 
            $resultat[] = $cours;
        }

        return new Response(httpCode: 200, responseString: json_encode($resultat));
    }

    // GET ?action=courses&withExercises-> liste des cours avec leurs exercices.
    public function getCoursesWithExercises() {
        $cours = $this->model->getListeCours();

        $resultat = array();
        foreach ($cours as $c) {
            $exercices = $this->model->getExercisesByCourse($c["id"]);

            $listeExercices = array();
            foreach ($exercices as $exo) {
                $e = array();
                $e["id"] = (int) $exo["id"];
                $e["name"] = $exo["name"];
                $e["description"] = $exo["description"];
                $e["finished"] = ($exo["finished"] == 1);
                $listeExercices[] = $e;
            }

            $unCours = array();
            $unCours["id"] = (int) $c["id"];
            $unCours["name"] = $c["name"];
            $unCours["deadline"] = $c["deadline"];
            $unCours["exercises"] = $listeExercices;
            $resultat[] = $unCours;
        }

        return new Response(httpCode: 200, responseString: json_encode($resultat));
    }

    // GET ?action=courses&id=X -> details d'un cours + pourcentage d'exercices termines.
    public function getCourseDetails($id): Response {
        $cours = $this->model->getCourseById($id);

        
        if ($cours == null) {
            return new Response(httpCode: 404);
        }

        $exercices = $this->model->getExercisesByCourse($id);

        // Compter les exercices termines pour calculer le pourcentage.
        $nbTotal = count($exercices);
        $nbTermines = 0;
        foreach ($exercices as $exo) {
            if ($exo["finished"] == 1) {
                $nbTermines = $nbTermines + 1;
            }
        }

        // Pourcentage entier
        if ($nbTotal == 0) {
            $pourcentage = 0;
        } else {
            $pourcentage = (int) round($nbTermines / $nbTotal * 100);
        }

        // Construire la liste des exercices avec les bons types.
        $listeExercices = array();
        foreach ($exercices as $exo) {
            $e = array();
            $e["id"] = (int) $exo["id"];
            $e["name"] = $exo["name"];
            $e["description"] = $exo["description"];
            $e["finished"] = ($exo["finished"] == 1);
            $listeExercices[] = $e;
        }

        $resultat = array();
        $resultat["id"] = (int) $cours["id"];
        $resultat["name"] = $cours["name"];
        $resultat["deadline"] = $cours["deadline"];
        $resultat["exercises"] = $listeExercices;
        $resultat["completionPercentage"] = $pourcentage;

        return new Response(httpCode: 200, responseString: json_encode($resultat));
    }

    // POST ?action=courses -> creation d'un cours.
    public function addCourse() {
        // Le nom est obligatoire.
        if (!isset($_POST["name"]) || $_POST["name"] == "") {
            return new Response(httpCode: 400);
        }
        $name = $_POST["name"];

        // La deadline est optionnelle.
        $deadline = null;
        if (isset($_POST["deadline"]) && $_POST["deadline"] != "") {
            $deadline = $_POST["deadline"];
        }

        $id = $this->model->addCourse($name, $deadline);

        $resultat = array();
        $resultat["id"] = (int) $id;
        return new Response(httpCode: 201, responseString: json_encode($resultat));
    }

    // DELETE ?action=courses&id=X -> suppression d'un cours (+ ses exercices en cascade).
    public function deleteCourse($id) {
        $nbSupprimes = $this->model->deleteCourse($id);

        // Aucune ligne supprimee -> l'id n'existait pas -> 404
        if ($nbSupprimes == 0) {
            $resultat = "Aucun cours avec cet id";
            return new Response(httpCode: 404,responseString: json_encode($resultat));
        }

        $resultat = array();
        $resultat["success"] = true;
        return new Response(httpCode: 200, responseString: json_encode($resultat));
    }

    // POST ?action=exercises -> creation d'un exercice
    public function addExercise() {
        // Les trois parametres sont obligatoires.
        if (!isset($_POST["courseId"]) || $_POST["courseId"] == "") {
            return new Response(httpCode: 400);
        }
        if (!isset($_POST["name"]) || $_POST["name"] == "") {
            return new Response(httpCode: 400);
        }
        if (!isset($_POST["description"]) || $_POST["description"] == "") {
            return new Response(httpCode: 400);
        }
        $courseId = $_POST["courseId"];
        $name = $_POST["name"];
        $description = $_POST["description"];

        // Le cours doit exister
        $cours = $this->model->getCourseById($courseId);
        if ($cours == null) {
            return new Response(httpCode: 404);
        }

        $id = $this->model->addExercise($courseId, $name, $description);

        $resultat = array();
        $resultat["id"] = (int) $id;
        return new Response(httpCode: 201, responseString: json_encode($resultat));
    }

    // DELETE ?action=exercises&id=X -> suppression d'un exercice.
    public function deleteExercise($id) {
        $nbSupprimes = $this->model->deleteExercise($id);

        if ($nbSupprimes == 0) {
            return new Response(httpCode: 404);
        }

        $resultat = array();
        $resultat["success"] = true;
        return new Response(httpCode: 200, responseString: json_encode($resultat));
    }

    // GET ?action=latecourses -> cours en retard + nombre d'exercices restants.
    public function getLateCourses() {
        $cours = $this->model->getLateCourses();

        $resultat = array();
        foreach ($cours as $c) {
            $nbRestants = $this->model->countRemainingExercises($c["id"]);

            $unCours = array();
            $unCours["id"] = (int) $c["id"];
            $unCours["name"] = $c["name"];
            $unCours["deadline"] = $c["deadline"];
            $unCours["remainingExercises"] = (int) $nbRestants;
            $resultat[] = $unCours;
        }

        return new Response(httpCode: 200, responseString: json_encode($resultat));
    }
}

?>
