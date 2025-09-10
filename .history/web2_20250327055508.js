document.addEventListener("DOMContentLoaded", function () {
    var swiper = new Swiper(".swiper", {
      slidesPerView: 1, 
      spaceBetween: 20,
      loop: true,
      navigation: {
        nextEl: ".C1",
        prevEl: ".C2",
        el: ".C3",
      },
      pagination: {
        el: ".C3",
        clickable: true,
      },
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
    });
  });
