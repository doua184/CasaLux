document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.champ');
    const inputs = form.querySelectorAll('input');
    const submitButton = form.querySelector('.login-btn');

    // Fonction pour valider l'email
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Fonction pour valider le mot de passe
    function validatePassword(password) {
        return password.length >= 8;
    }

    // Fonction pour afficher les messages d'erreur
    function showError(input, message) {
        const inputBox = input.parentElement;
        const error = document.createElement('div');
        error.className = 'error-message';
        error.textContent = message;
        
        // Supprimer l'ancien message d'erreur s'il existe
        const existingError = inputBox.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        
        inputBox.appendChild(error);
        inputBox.classList.add('error');
    }

    // Fonction pour supprimer les messages d'erreur
    function removeError(input) {
        const inputBox = input.parentElement;
        const error = inputBox.querySelector('.error-message');
        if (error) {
            error.remove();
        }
        inputBox.classList.remove('error');
    }

    // Ajouter les écouteurs d'événements pour chaque champ
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            removeError(this);
        });
    });

    // Gérer la soumission du formulaire
    submitButton.addEventListener('click', function(e) {
        e.preventDefault();
        let isValid = true;

        // Valider chaque champ
        inputs.forEach(input => {
            if (input.type !== 'checkbox') {
                if (!input.value.trim()) {
                    showError(input, 'Ce champ est requis');
                    isValid = false;
                } else if (input.type === 'email' && !validateEmail(input.value)) {
                    showError(input, 'Veuillez entrer une adresse email valide');
                    isValid = false;
                } else if (input.type === 'password') {
                    if (input.placeholder === 'Mot de passe' && !validatePassword(input.value)) {
                        showError(input, 'Le mot de passe doit contenir au moins 8 caractères');
                        isValid = false;
                    } else if (input.placeholder === 'Confirmer le mot de passe') {
                        const password = form.querySelector('input[placeholder="Mot de passe"]').value;
                        if (input.value !== password) {
                            showError(input, 'Les mots de passe ne correspondent pas');
                            isValid = false;
                        }
                    }
                }
            }
        });

        // Vérifier si les conditions sont acceptées
        const checkbox = form.querySelector('input[type="checkbox"]');
        if (!checkbox.checked) {
            showError(checkbox, 'Vous devez accepter les termes et conditions');
            isValid = false;
        }

        if (isValid) {
            // Ici, vous pouvez ajouter le code pour envoyer les données au serveur
            alert('Inscription réussie !');
            form.reset();
        }
    });
}); 