
    const form = document.querySelector('.champ');
    const inputs = form.querySelectorAll('input');
    const submitButton = form.querySelector('.login-btn');

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validatePassword(password) {
        return password.length >= 8;
    }
    function validateName(name) {
        const re = /^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/;
        return re.test(name.trim());
    }
    

    function showError(input, message) {
        const inputBox = input.parentElement;
        const existingError = inputBox.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        const error = document.createElement('div');
        error.className = 'error-message';
        error.textContent = message;
        inputBox.appendChild(error);
        
    }

    function removeError(input) {
        const inputBox = input.parentElement;
        const error = inputBox.querySelector('.error-message');
        if (error) {
            error.remove();
        }
        inputBox.classList.remove('error');
    }

    inputs.forEach(input => {
        input.addEventListener('input', function() {
            removeError(this);
        });
    });

    submitButton.addEventListener('click', function(e) {
        e.preventDefault();
        let isValid = true;

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
                        
                        
                }else if (input.placeholder === 'Nom' ) {
                    if (!validateName(input.value)) {
                        showError(input, 'Veuillez entrer un nom valide');
                        isValid = false;
                    }
                }else if (input.placeholder === 'Prénom' ) {
                    if (!validateName(input.value)) {
                        showError(input, 'Veuillez entrer un Prénom valide');
                        isValid = false;
                    }
                }
                
            }
        });

        const checkbox = form.querySelector('input[type="checkbox"]');
        if (!checkbox.checked) {
            showError(checkbox, 'Vous devez accepter les termes et conditions');
            isValid = false;
        }

        if (isValid) {
            alert('Inscription réussie !');
            form.reset();
        }
    });

