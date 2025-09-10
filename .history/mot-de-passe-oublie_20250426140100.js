document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.form');
    const emailInput = form.querySelector('input[type="email"]');
    const submitButton = form.querySelector('.reset-btn');

    // Fonction pour valider l'email
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
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

    // Fonction pour afficher le message de succès
    function showSuccess(message) {
        const successMessage = document.createElement('div');
        successMessage.className = 'success-message';
        successMessage.textContent = message;
        form.appendChild(successMessage);
    }

    // Ajouter l'écouteur d'événements pour le champ email
    emailInput.addEventListener('input', function() {
        removeError(this);
    });

    // Gérer la soumission du formulaire
    submitButton.addEventListener('click', function(e) {
        e.preventDefault();
        let isValid = true;

        // Valider l'email
        if (!emailInput.value.trim()) {
            showError(emailInput, 'Veuillez entrer votre adresse e-mail');
            isValid = false;
        } else if (!validateEmail(emailInput.value)) {
            showError(emailInput, 'Veuillez entrer une adresse e-mail valide');
            isValid = false;
        }

        if (isValid) {
            // Envoyer la demande au serveur
            const formData = new FormData();
            formData.append('email', emailInput.value);

            fetch('reset_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(data.message);
                    form.reset();
                    
                    // Rediriger vers la page de connexion après 3 secondes
                    setTimeout(() => {
                        window.location.href = 'index2.html';
                    }, 3000);
                } else {
                    showError(emailInput, data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError(emailInput, 'Une erreur est survenue. Veuillez réessayer.');
            });
        }
    });
}); 