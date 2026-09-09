/* Bedeck admin — mobile sidebar nav toggle */
(function () {
  "use strict";
  var toggle = document.querySelector("[data-admin-menu-toggle]");
  var links = document.querySelector("[data-admin-side-links]");
  if (!toggle || !links) return;

  toggle.addEventListener("click", function () {
    var open = toggle.getAttribute("aria-expanded") === "true";
    toggle.setAttribute("aria-expanded", String(!open));
    links.classList.toggle("is-open", !open);
  });
  links.addEventListener("click", function (e) {
    if (e.target.closest("a")) {
      links.classList.remove("is-open");
      toggle.setAttribute("aria-expanded", "false");
    }
  });
})();

/* Bedeck admin — product image preview + client-side info hint */
(function () {
  "use strict";
  var input = document.querySelector("[data-image-input]");
  if (!input) return;
  var preview = document.querySelector("[data-image-preview]");
  var note = document.querySelector("[data-dim-note]");

  input.addEventListener("change", function () {
    var file = input.files && input.files[0];
    if (!file) return;
    var url = URL.createObjectURL(file);
    var img = new Image();
    img.onload = function () {
      if (preview) { preview.src = url; preview.classList.add("on"); }
      var w = img.naturalWidth, h = img.naturalHeight;
      if (note) {
        note.textContent = w + " × " + h + " px — Ready. System will auto-fit, center & convert to 1200 × 1200 and 600 × 600 WEBP.";
        note.className = "dim-note good";
      }
    };
    img.src = url;
  });
})();
