const slider = document.querySelector(".announcement-slider");
let slideIndex = 0;

function slide() {
  slideIndex++;
  if (slideIndex >= slider.children.length) {
    slideIndex = 0;
  }
  slider.style.transform = `translateX(-${slideIndex * 100}%)`;
}

setInterval(slide, 5000); // Change slide every 5 seconds