document.addEventListener("DOMContentLoaded", function () {
  var swiper = new Swiper(".swiper", {
    slidesPerView: 1, 
    spaceBetween: 20,
    loop: true,
    navigation: {
      nextEl: ".C1", // Bouton suivant
      prevEl: ".C2", // Bouton précédent
    },
    pagination: {
      el: ".pagination", // Elément séparé pour la pagination
      clickable: true,
    },
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
  });
});
const swiper = new Swiper(".swiper", {
  loop: true,
});