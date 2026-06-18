<?php

class Model {
    //Attribut
    private $db;
    // Constructeur de la class
    public function __construct(bool $withErrors = false) {

        $hote = "localhost";
        $nomBase = "weba-te03-2026";
        $utilisateur = "root";
        $motDePasse = "";

        $dsn = "mysql:host=" . $hote . ";dbname=" . $nomBase . ";charset=utf8mb4";

        $this->db = new PDO($dsn, $utilisateur, $motDePasse);

        if ($withErrors) {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    // Requête GET : Liste des cours sans les exercices
    public function getListeCours() {
        $requete = $this->db->prepare("SELECT id, name, deadline FROM course");
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    // Requête GET : Détail du cours 1 
    public function getCourseById($id) {
        $requete = $this->db->prepare("SELECT id, name, deadline FROM course WHERE id = :id");
        $requete->execute(array(":id" => $id));
        $cours = $requete->fetch(PDO::FETCH_ASSOC);

        if ($cours == false) {
            return null;
        }
        return $cours;
    }

    //// Requête GET : Liste des cours avec les exercices
    public function getExercisesByCourse($courseId) {
        $requete = $this->db->prepare("SELECT id, name, description, finished FROM exercise WHERE courseId = :courseId");
        $requete->execute(array(":courseId" => $courseId));
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    //Requête POST : Ajout d'un cours et retourne l'id du nouveau cours.
    public function addCourse($name, $deadline) {
        $requete = $this->db->prepare("INSERT INTO course (name, deadline) VALUES (:name, :deadline)");
        $requete->execute(array(":name" => $name, ":deadline" => $deadline));
        return $this->db->lastInsertId();
    }

    // Requête DEL Suppresion d'un cours d'apres son id.(Cours 5 dans l'exemple)
    public function deleteCourse($id){
        $requete = $this->db->prepare("DELETE FROM course WHERE id = :id");
        $requete->execute(array(":id" => $id));
        return $requete->rowCount();
    }

    //Requête POST : Ajoute un exercice (toujours non termine au depart) et retourne son id.
    public function addExercise($courseId, $name, $description) {
        $requete = $this->db->prepare("INSERT INTO exercise (courseId, name, description, finished) VALUES (:courseId, :name, :description, 0)");
        $requete->execute(array(":courseId" => $courseId, ":name" => $name, ":description" => $description));
        return $this->db->lastInsertId();
    }

    //Requête DEL : Supprime un exercice d'apres son id. Retourne le nombre de lignes supprimees.
    public function deleteExercise($id) {
        $requete = $this->db->prepare("DELETE FROM exercise WHERE id = :id");
        $requete->execute(array(":id" => $id));
        return $requete->rowCount();
    }

    //Requête GET : Retourne les cours en retard : deadline renseignee (non null) ET deja passee.
    public function getLateCourses() {
        $requete = $this->db->query("SELECT id, name, deadline FROM course WHERE deadline IS NOT NULL AND deadline < NOW()");  
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compte les exercices non termines (finished = 0) d'un cours.
    public function countRemainingExercises($courseId) {
        $requete = $this->db->prepare("SELECT COUNT(*) FROM exercise WHERE courseId = :courseId AND finished = 0");
        $requete->execute(array(":courseId" => $courseId));
        return $requete->fetchColumn();
    }
}
?>
