/* Bedeck admin — product image preview + client-side dimension hint */
(function () {
  "use strict";
  var input = document.querySelector("[data-image-input]");
  if (!input) return;
  var preview = document.querySelector("[data-image-preview]");
  var note = document.querySelector("[data-dim-note]");
  var MIN = 600;

  input.addEventListener("change", function () {
    var file = input.files && input.files[0];
    if (!file) return;
    var url = URL.createObjectURL(file);
    var img = new Image();
    img.onload = function () {
      if (preview) { preview.src = url; preview.classList.add("on"); }
      var w = img.naturalWidth, h = img.naturalHeight;
      var ratio = w / h;
      var ok = w >= MIN && h >= MIN && ratio >= 0.8 && ratio <= 1.25;
      if (note) {
        note.textContent = w + " × " + h + " px — " +
          (ok ? "looks good. It will be centre-cropped to 1200 × 1200 and 600 × 600 WEBP."
              : "needs to be at least 600 × 600 and roughly square (between 4:5 and 5:4).");
        note.className = "dim-note " + (ok ? "good" : "bad");
      }
    };
    img.src = url;
  });
})();
