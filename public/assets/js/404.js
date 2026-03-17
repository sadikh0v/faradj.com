const canvas = document.getElementById("gameCanvas");
if (canvas) {
  const ctx = canvas.getContext("2d");
  let pencil = { x: 60, y: 145, vy: 0, grounded: true };
  let obstacles = [];
  let score = 0;
  let gameOver = false;
  let frame = 0;

  function jump() {
    if (pencil.grounded) {
      pencil.vy = -12;
      pencil.grounded = false;
    }
  }

  document.addEventListener("keydown", (e) => {
    if (e.code === "Space") {
      e.preventDefault();
      jump();
    }
  });
  canvas.addEventListener("click", jump);
  canvas.addEventListener(
    "touchstart",
    (e) => {
      e.preventDefault();
      jump();
    },
    { passive: false }
  );

  function gameLoop() {
    if (gameOver) return;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "rgba(108,99,255,0.3)";
    ctx.fillRect(0, 175, canvas.width, 2);
    ctx.fillStyle = "#6c63ff";
    ctx.fillRect(pencil.x, pencil.y, 20, 30);
    ctx.fillStyle = "#ff6584";
    ctx.fillRect(pencil.x, pencil.y, 20, 6);
    pencil.vy += 0.6;
    pencil.y += pencil.vy;
    if (pencil.y >= 145) {
      pencil.y = 145;
      pencil.vy = 0;
      pencil.grounded = true;
    }
    frame++;
    if (frame % 90 === 0) {
      obstacles.push({
        x: canvas.width,
        y: 145,
        w: 16,
        h: 30 + Math.random() * 20,
      });
    }
    obstacles = obstacles.filter((obs) => {
      obs.x -= 4;
      ctx.fillStyle = "#ff6584";
      ctx.fillRect(obs.x, obs.y, obs.w, obs.h);
      if (
        pencil.x < obs.x + obs.w &&
        pencil.x + 20 > obs.x &&
        pencil.y < obs.y + obs.h
      ) {
        gameOver = true;
        ctx.fillStyle = "rgba(0,0,0,0.5)";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = "white";
        ctx.font = "bold 24px Montserrat";
        ctx.textAlign = "center";
        ctx.fillText("Oyun bitdi! Tap to restart", canvas.width / 2, canvas.height / 2);
        canvas.addEventListener(
          "click",
          () => location.reload(),
          { once: true }
        );
      }
      return obs.x + obs.w >= 0;
    });
    score++;
    const scoreEl = document.getElementById("score");
    if (scoreEl) scoreEl.textContent = Math.floor(score / 6);
    requestAnimationFrame(gameLoop);
  }
  gameLoop();
}
