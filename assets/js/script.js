// nav 1 and 2
var prevScrollpos = window.pageYOffset;

window.onscroll = function() {
    
    var currentScrollPos = window.pageYOffset;
    if (prevScrollpos > currentScrollPos) {
        document.getElementById("navbar").style.top = "89px";
    } else {
        document.getElementById("navbar").style.top = "-89px";
    }
    prevScrollpos = currentScrollPos;
    
    
    
    scrollFunction()


};

function scrollFunction() {
  if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
    document.getElementById("logo").style.width = "113px";
    document.getElementById("nav").style.padding = "8px";
  } else {
    document.getElementById("logo").style.width = "145px";
    document.getElementById("nav").style.padding = "0px";
  }
}

// slider

let slideIndex = 0;
showSlides();

function showSlides() {
  let i;
  let slides = document.getElementsByClassName("mySlides");
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
