<?php
echo("bonjour");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
$servername = "localhost";
$username_db = "root";
$password_db = ""; // Vide par défaut sur XAMPP
$dbname = "casalux";

// Créer la connexion
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer et sécuriser les données
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

   

        // Préparer la requête SQL
        $sql = "INSERT INTO users (nom, prenom, email, username, password)
                VALUES ('$nom', '$prenom', '$email', '$username', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo " Inscription réussie ! Bienvenue $prenom $nom.";
        } else {
            echo " Erreur : " . $conn->error;
        }
   
}

// Fermer la connexion
$conn->close();
?>
