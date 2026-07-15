window.addEventListener('scroll', function() {
  document.getElementById('nav').classList.toggle('nav-compact', window.scrollY > 60);
});

// slider

let slideIndex = 0;
showSlides();

function showSlides() {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  if (!slides.length) {
    return;
  }
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}    
  slides[slideIndex-1].style.display = "block";  
  setTimeout(showSlides, 2000); // Change image every 2 seconds
}

function initializeImagePreview(inputId, imgSelector) {
  document.addEventListener("DOMContentLoaded", function() {
      const productImageInput = document.getElementById(inputId);
      const imagePreview = document.querySelector(imgSelector);
      if (!productImageInput || !imagePreview) {
        return;
      }

      productImageInput.addEventListener('change', function(e) {
          const file = this.files[0];
          if (file) {
              const reader = new FileReader();

              reader.onload = function(e) {
                  imagePreview.src = e.target.result;
              };

              reader.readAsDataURL(file);
          }
      });
  });
}

initializeImagePreview('productImage', '.upload-button img');
