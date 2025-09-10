<?php
require_once 'config.php';

// Vérifier si un token est présent dans l'URL
if (!isset($_GET['token'])) {
    header('Location: index2.html');
    exit;
}

$token = $_GET['token'];
$valid = false;
$message = '';

try {
    // Vérifier si le token est valide et n'a pas expiré
    $stmt = $pdo->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expiry > NOW() AND used = 0");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();

    if ($reset) {
        $valid = true;
    } else {
        $message = 'Le lien de réinitialisation est invalide ou a expiré.';
    }
} catch (PDOException $e) {
    $message = 'Une erreur est survenue. Veuillez réessayer.';
}

// Traiter la soumission du nouveau mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $response = array();

    // Valider le mot de passe
    if (strlen($password) < 8) {
        $response['success'] = false;
        $response['message'] = 'Le mot de passe doit contenir au moins 8 caractères.';
    } elseif ($password !== $confirm_password) {
        $response['success'] = false;
        $response['message'] = 'Les mots de passe ne correspondent pas.';
    } else {
        try {
            // Hasher le nouveau mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Mettre à jour le mot de passe
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $reset['user_id']]);

            // Marquer le token comme utilisé
            $stmt = $pdo->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
            $stmt->execute([$token]);

            $response['success'] = true;
            $response['message'] = 'Votre mot de passe a été réinitialisé avec succès.';
        } catch (PDOException $e) {
            $response['success'] = false;
            $response['message'] = 'Une erreur est survenue. Veuillez réessayer.';
        }
    }

    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe - CasaLux</title>
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="mot-de-passe-oublie.css">
    <script src="https://kit.fontawesome.com/4cda2b3395.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="alert">
        <p class="alert-text">🚚 Free Shipping On All U.S. Orders $50+</p>
    </div>
    <nav class="nav">
        <div class="container nav__container">
            <div class="nav__logo">
                <a href="index.html#home" class="logo">CasaLux</a>
            </div>
            
            <ul class="nav__links" id="nav-links">
                <li><a href="index.html#home">Home</a></li>
                <li><a href="index.html#about">About</a></li>
                <li><a href="index.html#product">Products</a></li>
                <li><a href="index.html#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <section>
        <?php if ($valid): ?>
        <form action="" method="post" class="form">
            <h1>Réinitialisation du mot de passe</h1>
            <p class="description">Entrez votre nouveau mot de passe</p>
            
            <div class="input-box">
                <input type="password" name="password" placeholder="Nouveau mot de passe" required>
                <i class="fa-solid fa-lock"></i>
            </div>

            <div class="input-box">
                <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                <i class="fa-solid fa-lock"></i>
            </div>

            <button type="submit" class="reset-btn">Réinitialiser le mot de passe</button>
            
            <div class="back-link">
                <a href="index2.html">Retour à la connexion</a>
            </div>
        </form>
        <?php else: ?>
        <div class="form">
            <h1>Lien invalide</h1>
            <p class="description"><?php echo $message; ?></p>
            <div class="back-link">
                <a href="index2.html">Retour à la connexion</a>
            </div>
        </div>
        <?php endif; ?>
    </section>
    
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('reset_password_confirm.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = 'index2.html';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue. Veuillez réessayer.');
            });
        });
    </script>
</body>
</html> 