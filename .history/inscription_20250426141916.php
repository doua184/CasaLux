<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "casalux";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    die();
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation des données
    $errors = [];

    // Vérification des champs vides
    if (empty($nom)) $errors[] = "Le nom est requis";
    if (empty($prenom)) $errors[] = "Le prénom est requis";
    if (empty($email)) $errors[] = "L'email est requis";
    if (empty($username)) $errors[] = "Le nom d'utilisateur est requis";
    if (empty($password)) $errors[] = "Le mot de passe est requis";
    if (empty($confirm_password)) $errors[] = "La confirmation du mot de passe est requise";

    // Vérification de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format d'email invalide";
    }

    // Vérification de la correspondance des mots de passe
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas";
    }

    // Vérification si l'email existe déjà
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = "Cet email est déjà utilisé";
    }

    // Vérification si le nom d'utilisateur existe déjà
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        $errors[] = "Ce nom d'utilisateur est déjà utilisé";
    }

    // Si pas d'erreurs, insertion dans la base de données
    if (empty($errors)) {
        try {
            // Hashage du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Préparation de la requête
            $stmt = $conn->prepare("INSERT INTO users (nom, prenom, email, username, password) VALUES (?, ?, ?, ?, ?)");
            
            // Exécution de la requête
            $stmt->execute([$nom, $prenom, $email, $username, $hashed_password]);

            // Redirection vers la page de connexion
            header("Location: index2.html");
            exit();
        } catch(PDOException $e) {
            $errors[] = "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
?> 