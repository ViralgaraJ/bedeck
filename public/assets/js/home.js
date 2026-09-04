/* =====================================================================
   BEDECK INTERNATIONAL — homepage magic
   canvas aurora globe · headline split · scroll-scrubbed showcase
   ===================================================================== */
(function () {
  "use strict";
  var reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var raf = window.requestAnimationFrame.bind(window);

  /* ============ 1. HERO GLOBE ============ */
  var canvas = document.getElementById("hero-canvas");
  if (canvas && canvas.getContext) {
    var ctx = canvas.getContext("2d");
    var DPR = Math.min(window.devicePixelRatio || 1, 2);
    var W = 0, H = 0, cx = 0, cy = 0, R = 0;
    var ptr = { x: 0, y: 0, tx: 0, ty: 0 };
    var heroVisible = true;

    function resize() {
      W = canvas.clientWidth; H = canvas.clientHeight;
      canvas.width = W * DPR; canvas.height = H * DPR;
      ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
      cx = W * 0.68; cy = H * 0.44; R = Math.min(W, H) * 0.34;
      if (W < 800) { cx = W * 0.5; cy = H * 0.42; R = Math.min(W, H) * 0.46; }
    }
    resize();
    window.addEventListener("resize", resize);
    window.addEventListener("pointermove", function (e) {
      ptr.tx = (e.clientX / window.innerWidth - 0.5) * 2;
      ptr.ty = (e.clientY / window.innerHeight - 0.5) * 2;
    }, { passive: true });

    if ("IntersectionObserver" in window) {
      new IntersectionObserver(function (en) {
        heroVisible = en[0].isIntersecting;
        if (heroVisible && !reduce) raf(frame);
      }, { threshold: 0 }).observe(canvas);
    }

    var pts = [], LAT = 26, LON = 40;
    for (var i = 1; i < LAT; i++) {
      var phi = Math.PI * (i / LAT);
      for (var j = 0; j < LON; j++) {
        var th = 2 * Math.PI * (j / LON);
        pts.push({ x: Math.sin(phi) * Math.cos(th), y: Math.cos(phi), z: Math.sin(phi) * Math.sin(th) });
      }
    }
    var rings = [];
    for (var r = 0; r < 6; r++) {
      rings.push({ tilt: (Math.random() - 0.5) * 1.8, phase: Math.random() * 6.28, rad: 1.04 + r * 0.11, spd: 0.003 + Math.random() * 0.006 });
    }
    var sparks = [];
    for (var s = 0; s < 34; s++) {
      sparks.push({ a: Math.random() * 6.28, y: (Math.random() - 0.5) * 2, rad: 1.25 + Math.random() * 0.55, spd: 0.002 + Math.random() * 0.005 });
    }

    var rot = 0;
    function project(p, ry) {
      var c = Math.cos(ry), s2 = Math.sin(ry);
      var x = p.x * c - p.z * s2, z = p.x * s2 + p.z * c, y = p.y;
      var tx = -0.42;
      var y2 = y * Math.cos(tx) - z * Math.sin(tx);
      var z2 = y * Math.sin(tx) + z * Math.cos(tx);
      var k = 1 / (2.5 - z2);
      return { sx: cx + x * R * k * 2.2 + ptr.x * 16, sy: cy + y2 * R * k * 2.2 + ptr.y * 16, d: z2 };
    }

    function frame() {
      ptr.x += (ptr.tx - ptr.x) * 0.05;
      ptr.y += (ptr.ty - ptr.y) * 0.05;
      if (!reduce) rot += 0.0016;
      ctx.clearRect(0, 0, W, H);

      var g = ctx.createRadialGradient(cx, cy, R * 0.15, cx, cy, R * 2.6);
      g.addColorStop(0, "rgba(37,99,235,0.30)");
      g.addColorStop(0.45, "rgba(34,211,238,0.14)");
      g.addColorStop(1, "rgba(4,8,20,0)");
      ctx.fillStyle = g;
      ctx.fillRect(0, 0, W, H);

      for (var i = 0; i < pts.length; i++) {
        var pr = project(pts[i], rot);
        var a = 0.1 + (pr.d + 1) * 0.34;
        ctx.beginPath();
        ctx.fillStyle = "rgba(165,205,255," + a.toFixed(3) + ")";
        ctx.arc(pr.sx, pr.sy, 0.5 + (pr.d + 1) * 1.15, 0, 6.283);
        ctx.fill();
      }

      for (var m = 0; m < LON; m += 8) {
        ctx.beginPath();
        for (var k2 = 0; k2 <= LAT; k2++) {
          var ph = Math.PI * (k2 / LAT), tt = 2 * Math.PI * (m / LON);
          var pr2 = project({ x: Math.sin(ph) * Math.cos(tt), y: Math.cos(ph), z: Math.sin(ph) * Math.sin(tt) }, rot);
          k2 ? ctx.lineTo(pr2.sx, pr2.sy) : ctx.moveTo(pr2.sx, pr2.sy);
        }
        ctx.strokeStyle = "rgba(140,180,255,0.10)";
        ctx.stroke();
      }

      for (var ri = 0; ri < rings.length; ri++) {
        var ring = rings[ri];
        if (!reduce) ring.phase += ring.spd;
        ctx.beginPath();
        for (var q = 0; q <= 72; q++) {
          var ang = (q / 72) * 6.283;
          var pt = { x: Math.cos(ang) * ring.rad, y: Math.sin(ring.tilt) * Math.sin(ang) * ring.rad, z: Math.cos(ring.tilt) * Math.sin(ang) * ring.rad };
          var pj = project(pt, rot * 0.6 + ring.phase);
          q ? ctx.lineTo(pj.sx, pj.sy) : ctx.moveTo(pj.sx, pj.sy);
        }
        ctx.strokeStyle = ri % 2 ? "rgba(59,130,246,0.32)" : "rgba(34,211,238,0.26)";
        ctx.lineWidth = 1.2;
        ctx.stroke();
      }

      for (var si = 0; si < sparks.length; si++) {
        var sp = sparks[si];
        if (!reduce) sp.a += sp.spd;
        var sj = project({ x: Math.cos(sp.a) * sp.rad, y: sp.y, z: Math.sin(sp.a) * sp.rad }, rot);
        ctx.beginPath();
        ctx.fillStyle = "rgba(245,185,63," + (0.2 + (sj.d + 1) * 0.4).toFixed(3) + ")";
        ctx.arc(sj.sx, sj.sy, 1.7, 0, 6.283);
        ctx.fill();
      }

      if (!reduce && heroVisible) raf(frame);
    }
    frame();
  }

  /* ============ 2. HERO BACKGROUND — YouTube video (desktop) / photo montage (fallback) ============ */
  var videoBox = document.querySelector("[data-hero-video]");
  var photos = Array.prototype.slice.call(document.querySelectorAll("[data-hero-photo]"));
  var montageStarted = false, videoStarted = false;

  function startMontage() {
    if (montageStarted || !photos.length) return;
    montageStarted = true;
    photos[0].classList.add("is-active");
    if (photos.length > 1 && !reduce) {
      var pi = 0;
      setInterval(function () {
        photos[pi].classList.remove("is-active");
        pi = (pi + 1) % photos.length;
        photos[pi].classList.add("is-active");
      }, 5000);
    }
  }

  function startVideo() {
    if (videoStarted || montageStarted) return;
    if (!videoBox || reduce) return;
    if (videoBox.querySelector("video")) {
      videoStarted = true;
      videoBox.classList.add("is-on");
      return;
    }
    var vid = videoBox.getAttribute("data-hero-video");
    if (!vid) return;
    videoStarted = true;
    var params = [
      "autoplay=1", "mute=1", "controls=0", "loop=1", "playlist=" + vid,
      "playsinline=1", "modestbranding=1", "rel=0", "iv_load_policy=3",
      "disablekb=1", "fs=0", "cc_load_policy=0"
    ].join("&");
    var iframe = document.createElement("iframe");
    iframe.src = "https://www.youtube-nocookie.com/embed/" + vid + "?" + params;
    iframe.title = "Background video";
    iframe.setAttribute("frameborder", "0");
    iframe.setAttribute("tabindex", "-1");
    iframe.setAttribute("aria-hidden", "true");
    iframe.allow = "autoplay; encrypted-media; picture-in-picture";
    iframe.addEventListener("load", function () {
      setTimeout(function () { videoBox.classList.add("is-on"); }, 500);
    });
    videoBox.appendChild(iframe);
  }

  // Poster frame immediately so the hero is never blank.
  if (photos.length) photos[0].classList.add("is-active");
  // Prefer the video everywhere; the montage only kicks in for reduced-motion users.
  startVideo();
  window.addEventListener("load", startVideo);
  setTimeout(function () { if (!videoStarted) startMontage(); }, 1400);

  /* ============ 3. HEADLINE WORD SPLIT ============ */
  var h1 = document.querySelector("[data-hero-heading]");
  if (h1 && !h1.dataset.split) {
    h1.dataset.split = "1";
    var words = h1.textContent.trim().split(/\s+/);
    h1.textContent = "";
    words.forEach(function (w, idx) {
      var span = document.createElement("span");
      span.className = "word";
      span.textContent = w + (idx < words.length - 1 ? " " : "");
      span.style.animationDelay = (0.2 + idx * 0.09) + "s";
      h1.appendChild(span);
    });
  }

})();
