/* =====================================================================
   BEDECK INTERNATIONAL — shared interaction layer
   header · scroll progress · parallax aurora · cursor spotlight
   glass tilt + glare · reveal · count-up · magnetic buttons
   ===================================================================== */
(function () {
  "use strict";

  var reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var fine = window.matchMedia("(pointer: fine)").matches;
  var raf = window.requestAnimationFrame.bind(window);

  /* ---------- Mobile navigation ---------- */
  var toggle = document.querySelector("[data-menu-toggle]");
  var nav = document.querySelector("[data-nav]");
  if (toggle && nav) {
    toggle.addEventListener("click", function () {
      var open = toggle.getAttribute("aria-expanded") === "true";
      toggle.setAttribute("aria-expanded", String(!open));
      nav.classList.toggle("is-open", !open);
      if (!open && header) header.classList.remove("is-hidden");
    });
    nav.addEventListener("click", function (e) {
      if (e.target.closest("a")) {
        nav.classList.remove("is-open");
        toggle.setAttribute("aria-expanded", "false");
      }
    });
  }

  /* ---------- Header height sync ----------
     Measures the real, rendered header height and exposes it as
     --header-space so page content always starts exactly at its bottom
     edge — no gap, no overlap, regardless of font metrics or breakpoint. */
  var headerEl = document.querySelector("[data-header]");
  function syncHeaderSpace() {
    if (!headerEl) return;
    var h = headerEl.getBoundingClientRect().height;
    if (h > 0) {
      document.documentElement.style.setProperty("--header-space", h + "px");
    }
  }
  syncHeaderSpace();
  window.addEventListener("resize", function () { raf(syncHeaderSpace); }, { passive: true });
  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(syncHeaderSpace);
  }
  window.addEventListener("load", syncHeaderSpace);

  /* ---------- Brand logo height sync ----------
     Matches the logo image's rendered height to the wordmark text block
     next to it, so the two share the same top and bottom edge instead of
     being vertically centered against each other. */
  var brandCopyEl = document.querySelector(".brand-copy");
  var brandLogoEl = document.querySelector(".brand-logo-img");
  function syncBrandLogoHeight() {
    if (!brandCopyEl || !brandLogoEl) return;
    var h = brandCopyEl.getBoundingClientRect().height;
    if (h > 0) {
      document.documentElement.style.setProperty("--brand-logo-h", h + "px");
    }
  }
  syncBrandLogoHeight();
  window.addEventListener("resize", function () { raf(syncBrandLogoHeight); }, { passive: true });
  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(syncBrandLogoHeight);
  }
  window.addEventListener("load", syncBrandLogoHeight);

  /* ---------- Scroll progress + header state ---------- */
  var bar = document.querySelector("[data-scroll-progress]");
  var header = document.querySelector("[data-header]");
  var aurora = document.querySelector("[data-aurora]");
  var parallaxEls = Array.prototype.slice.call(document.querySelectorAll("[data-parallax]"));
  var lastScroll = -1;
  var auroraShift = { sy: 0, px: 0, py: 0 };
  function paintAurora() {
    if (aurora) aurora.style.transform = "translate3d(" + auroraShift.px.toFixed(1) + "px," +
      (auroraShift.sy + auroraShift.py).toFixed(1) + "px,0)";
  }

  function onScroll() {
    var y = window.pageYOffset || document.documentElement.scrollTop || 0;
    if (y === lastScroll) return;
    var goingDown = y > lastScroll;
    lastScroll = y;

    var doc = document.documentElement;
    var max = doc.scrollHeight - doc.clientHeight;
    if (bar) bar.style.width = (max > 0 ? (y / max) * 100 : 0) + "%";
    if (header) {
      header.classList.toggle("is-scrolled", y > 24);
      var menuOpen = nav && nav.classList.contains("is-open");
      if (!menuOpen) {
        if (y > 120 && goingDown) {
          header.classList.add("is-hidden");
        } else if (!goingDown || y <= 120) {
          header.classList.remove("is-hidden");
        }
      }
    }

    if (!reduce) {
      auroraShift.sy = y * -0.06;
      paintAurora();
      for (var i = 0; i < parallaxEls.length; i++) {
        var el = parallaxEls[i];
        var rate = parseFloat(el.getAttribute("data-parallax")) || 0.15;
        var rect = el.getBoundingClientRect();
        var offset = (rect.top + rect.height / 2 - window.innerHeight / 2) * rate;
        el.style.transform = "translate3d(0," + offset.toFixed(1) + "px,0) scale(1.08)";
      }
    }
  }
  window.addEventListener("scroll", function () { raf(onScroll); }, { passive: true });
  onScroll();

  /* ---------- Cursor spotlight + aurora pointer drift ---------- */
  var spot = document.querySelector("[data-spotlight]");
  if (fine && !reduce) {
    document.body.classList.add("has-pointer");
    var px = window.innerWidth / 2, py = window.innerHeight / 2, tx = px, ty = py;
    var settleFrames = 0, ticking = false;
    function loop() {
      px += (tx - px) * 0.12;
      py += (ty - py) * 0.12;
      if (spot) spot.style.transform = "translate3d(" + px.toFixed(1) + "px," + py.toFixed(1) + "px,0)";
      auroraShift.px = (px / window.innerWidth - 0.5) * 30;
      auroraShift.py = (py / window.innerHeight - 0.5) * 24;
      paintAurora();
      // Stop the loop once the easing has visually settled; a new pointer move restarts it.
      if (Math.abs(tx - px) < 0.4 && Math.abs(ty - py) < 0.4) settleFrames++; else settleFrames = 0;
      if (settleFrames > 8) { ticking = false; return; }
      raf(loop);
    }
    window.addEventListener("pointermove", function (e) {
      tx = e.clientX; ty = e.clientY; settleFrames = 0;
      if (!ticking) { ticking = true; raf(loop); }
    }, { passive: true });
  }

  /* ---------- Glass tilt + glare ---------- */
  if (fine && !reduce) {
    var tiltSelector = "[data-tilt], .product-card, .category-grid > a, .partner-grid > a, .trust > div";
    var tilts = Array.prototype.slice.call(document.querySelectorAll(tiltSelector));
    tilts.forEach(function (el) {
      el.classList.add("tilt");
      var raf2 = null;
      el.addEventListener("pointermove", function (e) {
        if (raf2) return;
        raf2 = raf(function () {
          raf2 = null;
          var r = el.getBoundingClientRect();
          var cx = (e.clientX - r.left) / r.width;
          var cy = (e.clientY - r.top) / r.height;
          var rx = (0.5 - cy) * 7;
          var ry = (cx - 0.5) * 9;
          el.style.transform = "perspective(900px) rotateX(" + rx.toFixed(2) + "deg) rotateY(" + ry.toFixed(2) + "deg) translateY(-4px)";
          el.style.setProperty("--mx", (cx * 100).toFixed(1) + "%");
          el.style.setProperty("--my", (cy * 100).toFixed(1) + "%");
        });
      });
      el.addEventListener("pointerleave", function () { el.style.transform = ""; });
    });
  }

  /* ---------- Reveal on scroll ---------- */
  var revealables = Array.prototype.slice.call(document.querySelectorAll("[data-reveal]"));
  function showAllInView(pad) {
    revealables.forEach(function (el) {
      if (el.getBoundingClientRect().top < (window.innerHeight || 800) + (pad || 0)) el.classList.add("is-visible");
    });
  }
  if (reduce || !("IntersectionObserver" in window)) {
    revealables.forEach(function (el) { el.classList.add("is-visible"); });
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.08, rootMargin: "0px 0px -6% 0px" });
    revealables.forEach(function (el) { io.observe(el); });
    window.addEventListener("load", function () { showAllInView(0); });
    setTimeout(function () { showAllInView(240); }, 1500);
  }

  /* ---------- Count-up statistics ---------- */
  function countUp(el) {
    var target = parseFloat(el.getAttribute("data-count"));
    var suffix = el.getAttribute("data-suffix") || "";
    if (isNaN(target)) return;
    if (reduce) { el.textContent = target + suffix; return; }
    var start = performance.now(), dur = 1500;
    (function tick(now) {
      var p = Math.min((now - start) / dur, 1);
      el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))) + suffix;
      if (p < 1) raf(tick);
    })(performance.now());
  }
  var counters = Array.prototype.slice.call(document.querySelectorAll("[data-count]"));
  if (counters.length) {
    var done = new WeakSet();
    var run = function (el) { if (!done.has(el)) { done.add(el); countUp(el); } };
    if (!("IntersectionObserver" in window)) {
      counters.forEach(run);
    } else {
      var cio = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) { if (e.isIntersecting) { run(e.target); cio.unobserve(e.target); } });
      }, { threshold: 0.4 });
      counters.forEach(function (el) { cio.observe(el); });
      setTimeout(function () {
        counters.forEach(function (el) {
          if (el.getBoundingClientRect().top < (window.innerHeight || 800) + 120) run(el);
        });
      }, 2500);
    }
  }

  /* ---------- Magnetic buttons ---------- */
  if (fine && !reduce) {
    Array.prototype.slice.call(document.querySelectorAll(".button.primary")).forEach(function (btn) {
      btn.addEventListener("pointermove", function (e) {
        var r = btn.getBoundingClientRect();
        btn.style.transform = "translate(" + ((e.clientX - r.left - r.width / 2) * 0.18).toFixed(1) + "px," +
          ((e.clientY - r.top - r.height / 2) * 0.28 - 2).toFixed(1) + "px)";
      });
      btn.addEventListener("pointerleave", function () { btn.style.transform = ""; });
    });
  }

  /* ---------- Page Hero Background Carousel ---------- */
  var pageHeroCarousel = document.querySelector("[data-page-hero-carousel]");
  if (pageHeroCarousel) {
    var slides = Array.prototype.slice.call(pageHeroCarousel.querySelectorAll(".page-hero__slide"));
    var dots = Array.prototype.slice.call(pageHeroCarousel.querySelectorAll(".page-hero__dot"));
    var currentIndex = 0;
    var timer = null;

    if (slides.length) slides[0].classList.add("is-active");

    var counterEl = pageHeroCarousel.querySelector("[data-carousel-counter]");

    function updateCounter() {
      if (counterEl) {
        var cur = String(currentIndex + 1).padStart(2, "0");
        var tot = String(slides.length).padStart(2, "0");
        counterEl.textContent = cur + " / " + tot;
      }
    }

    function goToSlide(index) {
      if (!slides.length) return;
      slides[currentIndex].classList.remove("is-active");
      if (dots[currentIndex]) dots[currentIndex].classList.remove("is-active");

      currentIndex = (index + slides.length) % slides.length;

      slides[currentIndex].classList.add("is-active");
      if (dots[currentIndex]) {
        dots[currentIndex].classList.add("is-active");
      }
      updateCounter();
    }
    updateCounter();

    function nextSlide() {
      goToSlide(currentIndex + 1);
    }

    function startAutoPlay() {
      stopAutoPlay();
      if (!reduce && slides.length > 1) {
        timer = setInterval(nextSlide, 2000);
      }
    }

    function stopAutoPlay() {
      if (timer) {
        clearInterval(timer);
        timer = null;
      }
    }

    startAutoPlay();
  }
})();

