/* =========================================
   ПЕРСОНАЛИЗАЦИЯ ПО ПОВЕДЕНИЮ
   ========================================= */
(function initPersonalization() {
  const STORAGE_KEY = "faradj_behavior";
  const path = window.location.pathname;

  let data = {};
  try {
    data = JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}");
  } catch {
    data = {};
  }

  data.visits = (data.visits || 0) + 1;
  data.lastPage = path;
  data.pages = data.pages || {};
  data.pages[path] = (data.pages[path] || 0) + 1;

  if (path === "/partners.php" || path === "/partners" || (data.pages["/partners.php"] || data.pages["/partners"] || 0) > 1) {
    data.interest = "partners";
  } else if (path === "/b2b.php" || path === "/b2b" || (data.pages["/b2b.php"] || data.pages["/b2b"] || 0) > 1) {
    data.interest = "b2b";
  } else if (path === "/events.php" || path === "/events" || (data.pages["/events.php"] || data.pages["/events"] || 0) > 1) {
    data.interest = "events";
  }

  localStorage.setItem(STORAGE_KEY, JSON.stringify(data));

  if (path === "/" || path === "/index.php") {
    const subtitle = document.querySelector(".hero-subtitle, .hero-desc, [data-hero-sub]");
    if (subtitle && data.interest) {
      const texts = {
        partners: {
          az: "Tərəfdaşlıq üçün ən yaxşı şərtlər — bizimlə əməkdaşlıq edin",
          ru: "Лучшие условия для партнёрства — сотрудничайте с нами",
          en: "Best partnership terms — collaborate with us",
        },
        b2b: {
          az: "Korporativ müştərilər üçün xüsusi qiymətlər və şərtlər",
          ru: "Специальные цены и условия для корпоративных клиентов",
          en: "Special prices and terms for corporate clients",
        },
        events: {
          az: "Ən son xəbərlər və tədbirlərdən xəbərdar olun",
          ru: "Будьте в курсе последних новостей и событий",
          en: "Stay updated with the latest news and events",
        },
      };
      const lang = document.documentElement.lang || "az";
      const text = texts[data.interest]?.[lang];
      if (text) {
        subtitle.style.transition = "opacity 0.5s ease";
        subtitle.style.opacity = "0";
        setTimeout(() => {
          subtitle.textContent = text;
          subtitle.style.opacity = "1";
        }, 300);
      }
    }
  }

  if (data.interest === "b2b" && (data.pages["/b2b.php"] || data.pages["/b2b"] || 0) > 0) {
    window._popupDelay = 60 * 1000;
  } else {
    window._popupDelay = 5 * 60 * 1000;
  }
})();

(function () {
  document.addEventListener("click", function (e) {
    const btn = e.target.closest("[data-lang]");
    if (!btn) return;
    e.preventDefault();
    e.stopImmediatePropagation();

    const lang = btn.dataset.lang;
    if (!["az", "ru", "en"].includes(lang)) return;

    const form = document.createElement("form");
    form.method = "POST";
    form.action = "/lang";

    const inp = document.createElement("input");
    inp.type = "hidden";
    inp.name = "lang";
    inp.value = lang;

    form.appendChild(inp);
    document.body.appendChild(form);
    form.submit();
  }, true);
})();

document.addEventListener("DOMContentLoaded", () => {
  /* =========================================
     1. МОБИЛЬНОЕ МЕНЮ (БУРГЕР) + OVERLAY
     ========================================= */
  (function() {
    const burgerBtn = document.getElementById("burgerBtn");
    const mobileNav = document.getElementById("mobileNav");
    const navOverlay = document.getElementById("navOverlay");
    const mobileClose = document.getElementById("mobileNavClose");

    if (!burgerBtn || !mobileNav) return;

    function openMenu() {
      mobileNav.classList.add("open");
      navOverlay?.classList.add("active");
      burgerBtn.classList.add("active");
      burgerBtn.setAttribute("aria-expanded", "true");
      navOverlay?.setAttribute("aria-hidden", "false");
      document.body.classList.add("menu-open");
    }

    function closeMenu() {
      mobileNav.classList.remove("open");
      navOverlay?.classList.remove("active");
      burgerBtn.classList.remove("active");
      burgerBtn.setAttribute("aria-expanded", "false");
      navOverlay?.setAttribute("aria-hidden", "true");
      document.body.classList.remove("menu-open");
    }

    burgerBtn.addEventListener("click", () => {
      mobileNav.classList.contains("open") ? closeMenu() : openMenu();
    });

    mobileClose?.addEventListener("click", closeMenu);
    navOverlay?.addEventListener("click", closeMenu);

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && mobileNav?.classList.contains("open")) closeMenu();
    });

    mobileNav.querySelectorAll(".mobile-nav-link, .mobile-nav-birmarket, .mobile-cta-btn").forEach((el) => {
      el.addEventListener("click", closeMenu);
    });
  })();

  /* =========================================
       3. МАГНИТНОЕ ОТТАЛКИВАНИЕ (JS)
       ========================================= */
  const wrappers = document.querySelectorAll(".floating-wrapper");

  // Настройки физики
  const ACTIVATION_RADIUS = 300; // Радиус реакции (px). Ближе этого мышь начинает толкать.
  const MAX_DISPLACEMENT = 150; // На сколько максимум может отлететь предмет (px).
  const SMOOTHING = 0.1; // Плавность движения (0.05 - очень плавно, 0.2 - резко).

  // Храним текущие позиции для плавности (Lerp)
  // Map связывает HTML-элемент с его координатами X и Y
  const positions = new Map();

  wrappers.forEach((wrapper) => {
    positions.set(wrapper, { x: 0, y: 0 }); // Изначально смещение 0
  });

  let mouseX = -1000; // Уводим мышь за экран при старте
  let mouseY = -1000;

  document.addEventListener("mousemove", (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
  });

  function animateMagnetic() {
    wrappers.forEach((wrapper) => {
      // 1. Получаем центр предмета
      const rect = wrapper.getBoundingClientRect();
      const itemCenterX = rect.left + rect.width / 2;
      const itemCenterY = rect.top + rect.height / 2;

      // 2. Считаем расстояние до мыши
      const distX = itemCenterX - mouseX;
      const distY = itemCenterY - mouseY;
      const distance = Math.sqrt(distX * distX + distY * distY);

      let targetX = 0;
      let targetY = 0;

      // 3. Если мышь внутри радиуса - вычисляем отталкивание
      if (distance < ACTIVATION_RADIUS) {
        // Сила отталкивания (чем ближе, тем ближе к 1)
        const force = (ACTIVATION_RADIUS - distance) / ACTIVATION_RADIUS;

        // Вектор отталкивания (нормализуем и умножаем на силу и макс. дистанцию)
        // Мы хотим толкать ОТ мыши, поэтому используем distX/distance
        targetX = (distX / distance) * force * MAX_DISPLACEMENT;
        targetY = (distY / distance) * force * MAX_DISPLACEMENT;
      }

      // 4. Плавное движение к цели (Linear Interpolation)
      const currentPos = positions.get(wrapper);
      currentPos.x += (targetX - currentPos.x) * SMOOTHING;
      currentPos.y += (targetY - currentPos.y) * SMOOTHING;

      // 5. Применяем стиль
      wrapper.style.transform = `translate(${currentPos.x}px, ${currentPos.y}px)`;
    });

    requestAnimationFrame(animateMagnetic);
  }

  animateMagnetic();

});

/* =========================================
     Бесконечность / Авто-ход / Интерактивность
     ========================================= */

function initMarquee(containerSelector, trackSelector, speedValue) {
  const container = document.querySelector(containerSelector);
  const track = document.querySelector(trackSelector);

  if (!container || !track) return;

  // Функция для инициализации после загрузки всех ресурсов
  const start = () => {
    let isDragging = false;
    let startX;
    let scrollLeft;
    let xPosition = 0;
    let blockWidth = 0;

    // 1. Клонируем содержимое (оригинал + 2 клона)
    const originalContent = track.innerHTML;
    track.innerHTML = originalContent + originalContent + originalContent;

    const updateDimensions = () => {
      const items = track.querySelectorAll("img");
      if (items.length === 0) return;

      // Считаем ширину одного блока по смещению первой картинки второго клона
      const firstItem = items[0];
      const duplicateItem = items[Math.floor(items.length / 3)];
      blockWidth = duplicateItem.offsetLeft - firstItem.offsetLeft;

      // Если мы только начали, ставим в центр
      if (xPosition === 0) {
        xPosition = -blockWidth;
      }
    };

    updateDimensions();
    track.style.transform = `translate3d(${xPosition}px, 0, 0)`;

    function checkBounds() {
      // Плавный сброс без transition в CSS не виден глазу
      if (xPosition <= -(blockWidth * 2)) {
        xPosition += blockWidth;
      } else if (xPosition >= 0) {
        xPosition -= blockWidth;
      }
    }

    function animate() {
      if (!isDragging) {
        xPosition -= speedValue;
        checkBounds();
        track.style.transform = `translate3d(${xPosition}px, 0, 0)`;
      }
      requestAnimationFrame(animate);
    }

    animate();

    // Drag & Drop
    const onStart = (e) => {
      isDragging = true;
      container.style.cursor = "grabbing";
      startX = (e.pageX || e.touches[0].pageX) - container.offsetLeft;
      scrollLeft = xPosition;
    };

    const onEnd = () => {
      isDragging = false;
      container.style.cursor = "grab";
    };

    const onMove = (e) => {
      if (!isDragging) return;
      e.preventDefault();
      const x = (e.pageX || e.touches[0].pageX) - container.offsetLeft;
      const walk = x - startX;
      xPosition = scrollLeft + walk;
      checkBounds();
      track.style.transform = `translate3d(${xPosition}px, 0, 0)`;
    };

    container.addEventListener("mousedown", onStart);
    window.addEventListener("mouseup", onEnd);
    window.addEventListener("mousemove", onMove);
    container.addEventListener("touchstart", onStart, { passive: false });
    window.addEventListener("touchend", onEnd);
    container.addEventListener("touchmove", onMove, { passive: false });

    window.addEventListener("resize", updateDimensions);
  };

  // Ждем загрузки всех картинок внутри трека
  const images = track.querySelectorAll("img");
  let loadedCount = 0;
  if (images.length === 0) start();

  images.forEach((img) => {
    if (img.complete) {
      loadedCount++;
      if (loadedCount === images.length) start();
    } else {
      img.addEventListener("load", () => {
        loadedCount++;
        if (loadedCount === images.length) start();
      });
      img.addEventListener("error", () => {
        loadedCount++;
        if (loadedCount === images.length) start();
      });
    }
  });
}

// Запуск
initMarquee("#brandsMarquee", "#brandsMarquee .marquee-track", 0.8);
initMarquee("#clientsMarquee", "#clientsMarquee .marquee-track", -0.8);

/* =========================================
   ИСПРАВЛЕННАЯ РАКЕТА (JS)
   ========================================= */
document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("backToTop");
  if (!btn) return;

  const originalWrapper = btn.querySelector(".pencil-wrapper");
  const originalPencil = btn.querySelector(".pencil-icon");

  btn.addEventListener("click", function (e) {
    e.preventDefault();

    if (this.classList.contains("launching")) return;
    this.classList.add("launching");

    const rect = originalWrapper.getBoundingClientRect();
    const startLeft = rect.left + rect.width / 2;
    const startTop = rect.top;

    const flyer = document.createElement("div");
    flyer.className = "pencil-flyer";

    flyer.innerHTML = `
            <i class="fas fa-pencil-alt" style="transform: rotate(135deg); color: #fff; font-size: 1.3rem;"></i>
            <span style="
                position: absolute;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                width: 10px;
                height: 100px;
                background: linear-gradient(to bottom, #ff6b6b, #ff9f43, transparent);
                border-radius: 20px;
                filter: blur(2px);
                opacity: 0.8;
            "></span>
        `;

    Object.assign(flyer.style, {
      position: "fixed",
      left: `${startLeft}px`,
      top: `${startTop}px`,
      transform: "translateX(-50%)",
      zIndex: "10000",
      pointerEvents: "none",
      display: "flex",
      flexDirection: "column",
      alignItems: "center",
    });

    document.body.appendChild(flyer);

    originalWrapper.style.opacity = "0";

    const startScrollY = window.scrollY;
    const duration = 1800;
    let startTime = null;

    function step(currentTime) {
      if (!startTime) startTime = currentTime;
      const timeElapsed = currentTime - startTime;
      const progress = Math.min(timeElapsed / duration, 1);

      const ease = 1 - Math.pow(1 - progress, 4);

      window.scrollTo(0, startScrollY * (1 - ease));

      const currentLift = ease * 400;
      flyer.style.top = `${startTop - currentLift}px`;

      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        flyer.style.transition = "all 0.5s ease-in";
        flyer.style.top = "-500px";
        flyer.style.opacity = "0";

        setTimeout(() => {
          flyer.remove();
          originalWrapper.style.opacity = "1";
          btn.classList.remove("launching");
        }, 600);
      }
    }

    requestAnimationFrame(step);
  });
});


/* Scroll Progress Bar */
window.addEventListener("scroll", () => {
  const progress = document.getElementById("scrollProgress");
  if (!progress) return;
  const winScroll = document.documentElement.scrollTop;
  const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  const scrolled = height > 0 ? (winScroll / height) * 100 : 0;
  progress.style.width = scrolled + "%";
});

/* Animate on Scroll */
const animateObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        animateObserver.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.15 }
);

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".animate-on-scroll").forEach((el) => {
    animateObserver.observe(el);
  });
});

/* =========================================
   1. ЧЕРНИЛЬНЫЕ БРЫЗГИ ПРИ КЛИКЕ
   ========================================= */
(function initInkSplash() {
  const colors = [
    "#6c63ff", "#ff6584", "#22c55e",
    "#f59e0b", "#06b6d4", "#8b5cf6",
    "#ec4899", "#3b82f6",
  ];

  function createSplash(x, y) {
    const count = 10;

    for (let i = 0; i < count; i++) {
      const dot = document.createElement("div");
      const angle = (360 / count) * i + (Math.random() * 30 - 15);
      const dist = 20 + Math.random() * 55;
      const size = 3 + Math.random() * 7;
      const color = colors[Math.floor(Math.random() * colors.length)];
      const rad = (angle * Math.PI) / 180;
      const tx = Math.cos(rad) * dist;
      const ty = Math.sin(rad) * dist;
      const dur = 400 + Math.random() * 300;

      dot.style.cssText = `
        position: fixed;
        left: ${x}px;
        top: ${y}px;
        width: ${size}px;
        height: ${size}px;
        background: ${color};
        border-radius: 50%;
        pointer-events: none;
        z-index: 999998;
        transform: translate(-50%, -50%);
        --tx: ${tx}px;
        --ty: ${ty}px;
        animation: inkDrop ${dur}ms cubic-bezier(0.25,0.46,0.45,0.94) forwards;
        box-shadow: 0 0 ${size * 2}px ${color}66;
      `;

      document.body.appendChild(dot);
      setTimeout(() => dot.remove(), dur + 50);
    }

    const center = document.createElement("div");
    center.style.cssText = `
      position: fixed;
      left: ${x}px;
      top: ${y}px;
      width: 12px;
      height: 12px;
      background: radial-gradient(circle, #fff 0%, #6c63ff 70%);
      border-radius: 50%;
      pointer-events: none;
      z-index: 999999;
      transform: translate(-50%, -50%) scale(0);
      animation: inkCenter 0.4s ease forwards;
    `;
    document.body.appendChild(center);
    setTimeout(() => center.remove(), 450);
  }

  document.addEventListener("click", (e) => {
    if (
      e.target.closest(
        "input, textarea, select, form, " +
          ".admin-body, #cookieBanner, " +
          ".modal-overlay"
      )
    )
      return;

    createSplash(e.clientX, e.clientY);
  });
})();

/* =========================================
   NAV RADIO
   ========================================= */
(function initNavRadio() {
  const TRACKS = [
    { name: "Lofi Hip Hop Radio", ytId: "jfKfPfyJRdk", dur: "LIVE" },
    { name: "Chillhop Radio", ytId: "5yx6BWlEVcY", dur: "LIVE" },
    { name: "Jazz & Bossa Nova", ytId: "4oStw0r33so", dur: "LIVE" },
    { name: "Deep Focus Beats", ytId: "n61ULEU7CO0", dur: "LIVE" },
    { name: "Piano Study Music", ytId: "9RMHHwJ9Eqk", dur: "LIVE" },
    { name: "Ambient Study", ytId: "DWcJFNfaw9c", dur: "LIVE" },
    { name: "Chill Coding Beats", ytId: "hHW1oY26kxQ", dur: "LIVE" },
    { name: "Lo-fi Café", ytId: "lCOF9LN_Zxs", dur: "LIVE" },
  ];

  const widget = document.getElementById("navRadio");
  const btn = document.getElementById("navRadioBtn");
  const dropdown = document.getElementById("navRadioDropdown");
  const playBtn = document.getElementById("nrdPlay");
  const playIcon = document.getElementById("nrdPlayIcon");
  const prevBtn = document.getElementById("nrdPrev");
  const nextBtn = document.getElementById("nrdNext");
  const volInput = document.getElementById("nrdVol");
  const nowLabel = document.getElementById("nrdNow");
  const tracklist = document.getElementById("nrdTracklist");
  const bars = document.querySelectorAll(".nrd-bar");

  if (!widget) return;

  let isOpen = false;
  let isPlaying = false;
  let current = 0;
  let ytPlayer = null;
  let ytReady = false;

  bars.forEach((bar) => {
    bar.style.setProperty("--h", 6 + Math.random() * 22 + "px");
  });

  function loadYT() {
    if (window.YT && window.YT.Player) { ytReady = true; return; }
    const s = document.createElement("script");
    s.src = "https://www.youtube.com/iframe_api";
    document.head.appendChild(s);
  }

  window.onYouTubeIframeAPIReady = () => { ytReady = true; };
  loadYT();

  function buildTracklist() {
    if (!tracklist) return;
    tracklist.innerHTML = "";
    TRACKS.forEach((t, i) => {
      const item = document.createElement("button");
      item.className = "nrd-track-item" + (i === current ? " active" : "");
      item.innerHTML = `<span class="nrd-track-num">${i + 1}</span><span class="nrd-track-title">${t.name}</span><span class="nrd-track-dur">${t.dur}</span>`;
      item.addEventListener("click", () => { current = i; play(); });
      tracklist.appendChild(item);
    });
  }

  buildTracklist();

  function updateActiveTrack() {
    document.querySelectorAll(".nrd-track-item").forEach((el, i) => {
      el.classList.toggle("active", i === current);
    });
    if (nowLabel) nowLabel.textContent = TRACKS[current].name;
  }

  function createYTPlayer(ytId) {
    let container = document.getElementById("ytNrdContainer");
    if (!container) {
      container = document.createElement("div");
      container.id = "ytNrdContainer";
      container.style.cssText = "position:fixed;left:-9999px;top:-9999px;width:1px;height:1px;pointer-events:none;";
      document.body.appendChild(container);
    }
    ytPlayer?.destroy();
    ytPlayer = null;
    ytPlayer = new YT.Player("ytNrdContainer", {
      videoId: ytId,
      playerVars: { autoplay: 1, controls: 0, loop: 1, playlist: ytId },
      events: {
        onReady: (e) => { e.target.setVolume(parseInt(volInput?.value || 60)); e.target.playVideo(); },
        onStateChange: (e) => { if (e.data === YT.PlayerState.ENDED) nextTrack(); },
      },
    });
  }

  function play() {
    if (!ytReady) { setTimeout(play, 400); return; }
    createYTPlayer(TRACKS[current].ytId);
    isPlaying = true;
    widget.classList.add("playing");
    if (playIcon) playIcon.className = "fas fa-pause";
    updateActiveTrack();
  }

  function pause() {
    ytPlayer?.pauseVideo();
    isPlaying = false;
    widget.classList.remove("playing");
    if (playIcon) playIcon.className = "fas fa-play";
  }

  function nextTrack() {
    current = (current + 1) % TRACKS.length;
    if (isPlaying) play();
    else updateActiveTrack();
  }

  function prevTrack() {
    current = (current - 1 + TRACKS.length) % TRACKS.length;
    if (isPlaying) play();
    else updateActiveTrack();
  }

  btn?.addEventListener("click", (e) => {
    e.stopPropagation();
    isOpen = !isOpen;
    dropdown?.classList.toggle("open", isOpen);
  });

  document.addEventListener("click", (e) => {
    if (isOpen && !widget.contains(e.target)) {
      isOpen = false;
      dropdown?.classList.remove("open");
    }
  });

  playBtn?.addEventListener("click", () => (isPlaying ? pause() : play()));
  prevBtn?.addEventListener("click", prevTrack);
  nextBtn?.addEventListener("click", nextTrack);

  volInput?.addEventListener("input", () => {
    ytPlayer?.setVolume(parseInt(volInput.value));
  });

  updateActiveTrack();
})();

/* Testimonials carousel */
(function () {
  const track = document.getElementById("testimonialsTrack");
  if (!track) return;

  const origCards = Array.from(track.children);
  if (origCards.length < 2) return;

  origCards.forEach((c) => track.appendChild(c.cloneNode(true)));
  origCards.forEach((c) => track.insertBefore(c.cloneNode(true), track.firstChild));

  const allCards = Array.from(track.children);
  const total = allCards.length;
  const count = origCards.length;

  let current = count;
  let timer;
  let isTransitioning = false;

  function goTo(idx, animate = true) {
    if (!animate) {
      track.style.transition = "none";
    } else {
      track.style.transition = "transform 0.6s cubic-bezier(.4,0,.2,1)";
    }

    allCards.forEach((c, i) => c.classList.toggle("active", i === idx));

    const cardW = allCards[0].offsetWidth + 24;
    const wrapW = track.parentElement.offsetWidth;
    const offset = idx * cardW - wrapW / 2 + cardW / 2;
    track.style.transform = `translateX(-${offset}px)`;
    current = idx;
  }

  track.addEventListener("transitionend", () => {
    if (current >= total - count) {
      goTo(current - count, false);
    } else if (current < count) {
      goTo(current + count, false);
    }
    isTransitioning = false;
  });

  function next() {
    if (isTransitioning) return;
    isTransitioning = true;
    goTo(current + 1);
  }

  function startTimer() {
    clearInterval(timer);
    timer = setInterval(next, 4000);
  }

  track.addEventListener("click", function (e) {
    const card = e.target.closest(".testimonial-card");
    if (!card) return;
    const idx = allCards.indexOf(card);
    if (idx === current) return;
    clearInterval(timer);
    goTo(idx);
    startTimer();
  });

  setTimeout(() => {
    goTo(current, false);
    startTimer();
  }, 50);
})();

/* Smooth scroll for anchor links */
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('a[href^="#"]').forEach((a) => {
    a.addEventListener("click", function (e) {
      const target = document.querySelector(this.getAttribute("href"));
      if (!target || this.getAttribute("href") === "#") return;
      e.preventDefault();
      target.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  });
});

