// Animation de visibilité des éléments au scroll
function checkVisibility() {
    const elements = document.querySelectorAll('.deals__card');
    elements.forEach((element, index) => {
      const rect = element.getBoundingClientRect();
      
      if (rect.top <= window.innerHeight && rect.bottom >= 0) {
        element.classList.add('visible');
      } else {
        element.classList.remove('visible');
      }
    });
}

window.addEventListener('scroll', checkVisibility);
checkVisibility(); // Vérification initiale pour les éléments déjà visibles au chargement de la page

// ScrollReveal pour afficher chaque .deals__card un par un
const scrollRevealOption = {
  distance: "50px",
  origin: "bottom",
  duration: 1000,
  delay: 200, // Délai initial
};

// Initialisation de ScrollReveal
ScrollReveal().reveal('.deals__card', {
    distance: '50px',       // Distance de l'animation (déplacement de 50px)
    origin: 'bottom',      // Direction depuis laquelle l'élément apparaît (bas de l'écran)
    duration: 1000,        // Durée de l'animation (en millisecondes)
    delay: 200,            // Délai avant le début de l'animation pour chaque élément
    interval: 2,         // Intervalle entre chaque animation des éléments
  });
  









  