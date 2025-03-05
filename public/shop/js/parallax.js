window.addEventListener('scroll', function() {
    const centeredSection = document.querySelector('.parallax');
    const centeredSectionHeight = centeredSection.offsetHeight;
    const scrollPosition = window.pageYOffset;
  
    if (scrollPosition >= centeredSectionHeight) {
      centeredSection.style.position = 'relative';
    } else {
      centeredSection.style.position = 'sticky';
    }
  });