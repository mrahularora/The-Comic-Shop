
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