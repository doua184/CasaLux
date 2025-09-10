<?php
require_once 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $response = array();

    // Valider l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['success'] = false;
        $response['message'] = 'Veuillez entrer une adresse e-mail valide.';
        echo json_encode($response);
        exit;
    }

    try {
        // Vérifier si l'email existe dans la base de données
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $response['success'] = false;
            $response['message'] = 'Aucun compte associé à cette adresse e-mail.';
            echo json_encode($response);
            exit;
        }

        // Générer un token unique
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Sauvegarder le token dans la base de données
        $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $token, $expiry]);

        // Préparer l'email
        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset_password_confirm.php?token=" . $token;
        $to = $email;
        $subject = "Réinitialisation de votre mot de passe - CasaLux";
        $message = "Bonjour,\n\n";
        $message .= "Vous avez demandé la réinitialisation de votre mot de passe.\n";
        $message .= "Cliquez sur le lien suivant pour réinitialiser votre mot de passe :\n";
        $message .= $resetLink . "\n\n";
        $message .= "Ce lien expirera dans 1 heure.\n\n";
        $message .= "Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet e-mail.\n\n";
        $message .= "Cordialement,\nL'équipe CasaLux";
        $headers = "From: noreply@casalux.com";

        // Envoyer l'email
        if (mail($to, $subject, $message, $headers)) {
            $response['success'] = true;
            $response['message'] = 'Un e-mail de réinitialisation a été envoyé à votre adresse.';
        } else {
            $response['success'] = false;
            $response['message'] = 'Erreur lors de l\'envoi de l\'e-mail. Veuillez réessayer.';
        }
    } catch (PDOException $e) {
        $response['success'] = false;
        $response['message'] = 'Une erreur est survenue. Veuillez réessayer.';
    }

    echo json_encode($response);
    exit;
}
?> 