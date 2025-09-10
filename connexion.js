document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('section');
    const inputs = form.querySelectorAll('input');
    const submitButton = form.querySelector('.login-btn');

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
                }
            }
        });

        if (isValid) {
            // Ici, vous pouvez ajouter le code pour envoyer les données au serveur
            window.location.href = 'index.html';
        }
    });
}); 