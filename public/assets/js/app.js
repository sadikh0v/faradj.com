function launchConfetti() {
  if (typeof confetti !== "function") return;
  const duration = 2000;
  const end = Date.now() + duration;
  (function frame() {
    confetti({ particleCount: 3, angle: 60, spread: 55, origin: { x: 0 }, colors: ["#6c63ff", "#ff6584", "#4ade80"] });
    confetti({ particleCount: 3, angle: 120, spread: 55, origin: { x: 1 }, colors: ["#6c63ff", "#ff6584", "#4ade80"] });
    if (Date.now() < end) requestAnimationFrame(frame);
  })();
}

async function updateOrderCount() {
  try {
    const res = await fetch("/api/counter.php");
    const data = await res.json();
    const el = document.getElementById("orderCount");
    if (el) {
      el.style.transform = "scale(1.3)";
      el.style.transition = "transform 0.2s";
      el.textContent = data.count ?? 0;
      setTimeout(() => { el.style.transform = "scale(1)"; }, 200);
    }
  } catch (e) {}
}
if (document.getElementById("orderCount")) {
  updateOrderCount();
  setInterval(updateOrderCount, 60000);
}

async function loadInstagramFeed() {
  const grid = document.getElementById("instagramGrid");
  const section = document.getElementById("instagramSection");
  if (!grid) return;

  try {
    const res = await fetch("/api/instagram");
    const data = await res.json();

    if (!data.posts || !data.posts.length) {
      section?.remove();
      return;
    }

    grid.innerHTML = "";

    data.posts.forEach((post) => {
      const imgSrc = post.media_type === "VIDEO" ? post.thumbnail_url : post.media_url;
      if (!imgSrc) return;

      const caption = (post.caption || "").replace(/[<>]/g, "").substring(0, 90);
      const dateStr = post.timestamp
        ? new Date(post.timestamp).toLocaleDateString("az-AZ", {
            day: "2-digit",
            month: "short",
            year: "numeric",
          })
        : "";

      const card = document.createElement("a");
      card.className = "ig-card";
      card.href = post.permalink;
      card.target = "_blank";
      card.rel = "noopener noreferrer";
      card.setAttribute("aria-label", caption || "Instagram post");
      card.innerHTML = `
        <img src="${imgSrc}" alt="${caption.substring(0, 50)}" loading="lazy" decoding="async">
        <div class="ig-card-overlay">
          ${caption ? `<p class="ig-card-caption">${caption}</p>` : ""}
          <div class="ig-card-meta">
            <i class="fab fa-instagram" style="font-size:11px"></i>
            <span>${dateStr}</span>
          </div>
        </div>
      `;
      grid.appendChild(card);
    });
  } catch (err) {
    console.warn("Instagram feed error:", err);
    section?.remove();
  }
}

document.addEventListener("DOMContentLoaded", () => {
  loadInstagramFeed();
  // Particles
  const canvas = document.getElementById("particlesCanvas");
  if (canvas) {
    const ctx = canvas.getContext("2d");
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    const particles = Array.from({ length: 60 }, () => ({
      x: Math.random() * canvas.width,
      y: Math.random() * canvas.height,
      r: Math.random() * 2 + 0.5,
      dx: (Math.random() - 0.5) * 0.4,
      dy: (Math.random() - 0.5) * 0.4,
      opacity: Math.random() * 0.5 + 0.2,
    }));
    function drawParticles() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      particles.forEach((p) => {
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(108,99,255,${p.opacity})`;
        ctx.fill();
        p.x += p.dx;
        p.y += p.dy;
        if (p.x < 0 || p.x > canvas.width) p.dx *= -1;
        if (p.y < 0 || p.y > canvas.height) p.dy *= -1;
      });
      requestAnimationFrame(drawParticles);
    }
    drawParticles();
    window.addEventListener("resize", () => {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    });
  }

  // Typing effect (home hero) — full phrase once
  const typingEl = document.getElementById("typingText");
  if (typingEl) {
    const fullText = typingEl.dataset.typingText || "Biznes və Yaradıcılıq üçün İlham Mənbəyiniz";
    let i = 0;
    typingEl.textContent = "";
    typingEl.style.borderRight = "3px solid currentColor";
    typingEl.style.paddingRight = "4px";
    function typeChar() {
      if (i < fullText.length) {
        typingEl.textContent += fullText[i];
        i++;
        setTimeout(typeChar, 65);
      } else {
        typingEl.style.borderRight = "none";
      }
    }
    setTimeout(typeChar, 800);
  }

  // Exit Popup — персонализированный таймер (1 мин если B2B интерес, иначе 5 мин)
  if (!sessionStorage.getItem("offerShown")) {
    const delay = window._popupDelay ?? 5 * 60 * 1000;
    window._offerTimer = setTimeout(() => {
      const popup = document.getElementById("exitPopup");
      if (popup) {
        popup.classList.add("active");
        sessionStorage.setItem("offerShown", "true");
      }
    }, delay);
  }
  ["closeExitPopup", "skipExitPopup"].forEach((id) => {
    document.getElementById(id)?.addEventListener("click", () => {
      document.getElementById("exitPopup")?.classList.remove("active");
    });
  });

  // Sticky CTA
  const stickyCta = document.getElementById("stickyCta");
  if (stickyCta && !sessionStorage.getItem("ctaClosed")) {
    window.addEventListener("scroll", () => {
      const h = document.body.scrollHeight - window.innerHeight;
      if (h > 0 && window.scrollY / h > 0.4) stickyCta.classList.add("visible");
    });
    document.getElementById("closeStickyCta")?.addEventListener("click", () => {
      stickyCta.classList.remove("visible");
      sessionStorage.setItem("ctaClosed", "true");
    });
  }

  // Callback modal
  document.getElementById("callbackBtn")?.addEventListener("click", () => {
    document.getElementById("callbackModal")?.classList.add("active");
  });
  document.getElementById("callbackClose")?.addEventListener("click", () => {
    document.getElementById("callbackModal")?.classList.remove("active");
  });
  // Callback form handled by forms.js

  // Cookie Banner — show until accepted
  const cookieBanner = document.getElementById("cookieBanner");
  if (cookieBanner) {
    if (!localStorage.getItem("cookiesAccepted")) {
      cookieBanner.classList.add("visible");
    }
    document.getElementById("cookieAccept")?.addEventListener("click", () => {
      localStorage.setItem("cookiesAccepted", "true");
      cookieBanner.classList.remove("visible");
    });
    document.getElementById("cookieDecline")?.addEventListener("click", () => {
      cookieBanner.classList.remove("visible");
    });
  }

  // Language switcher: forms POST to /lang, active class set by PHP

  // Testimonials Slider
  const testimonialTrack = document.querySelector(".testimonial-track");
  const testimonialCards = document.querySelectorAll(".testimonial-card");
  const dotsContainer = document.getElementById("testimonialDots");

  if (testimonialTrack && testimonialCards.length > 0 && dotsContainer) {
    let currentIndex = 0;
    testimonialCards.forEach((_, i) => {
      const dot = document.createElement("span");
      dot.className = "testimonial-dot" + (i === 0 ? " active" : "");
      dot.dataset.index = i;
      dot.addEventListener("click", () => {
        currentIndex = i;
        updateSlider();
      });
      dotsContainer.appendChild(dot);
    });

    const updateSlider = () => {
      testimonialTrack.style.transform = `translateX(-${currentIndex * 100}%)`;
      dotsContainer.querySelectorAll(".testimonial-dot").forEach((d, i) => {
        d.classList.toggle("active", i === currentIndex);
      });
    };

    const nextSlide = () => {
      currentIndex = (currentIndex + 1) % testimonialCards.length;
      updateSlider();
    };
    const prevSlide = () => {
      currentIndex = (currentIndex - 1 + testimonialCards.length) % testimonialCards.length;
      updateSlider();
    };

    setInterval(nextSlide, 4000);

    // Swipe for mobile
    const slider = document.querySelector(".testimonials-slider");
    if (slider) {
      let touchStartX = 0;
      let touchEndX = 0;
      slider.addEventListener(
        "touchstart",
        (e) => {
          touchStartX = e.changedTouches[0].screenX;
        },
        { passive: true }
      );
      slider.addEventListener(
        "touchend",
        (e) => {
          touchEndX = e.changedTouches[0].screenX;
          const diff = touchStartX - touchEndX;
          if (Math.abs(diff) > 50) {
            diff > 0 ? nextSlide() : prevSlide();
          }
        },
        { passive: true }
      );
    }
  }

});
