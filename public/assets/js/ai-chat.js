(function initAIChat() {
  const toggleBtn = document.getElementById("aiToggle");
  const window_ = document.getElementById("aiWindow");
  const closeBtn = document.getElementById("aiClose");
  const input = document.getElementById("aiInput");
  const sendBtn = document.getElementById("aiSend");
  const messages = document.getElementById("aiMessages");
  const iconClose = document.querySelector(".ai-icon-closed");
  const iconOpen = document.querySelector(".ai-icon-open");

  if (!toggleBtn) return;

  let isOpen = false;
  let isLoading = false;
  let history = [];

  function toggle() {
    isOpen = !isOpen;
    window_?.classList.toggle("open", isOpen);
    if (iconClose) iconClose.style.display = isOpen ? "none" : "block";
    if (iconOpen) iconOpen.style.display = isOpen ? "block" : "none";
    if (isOpen) input?.focus();
  }

  toggleBtn.addEventListener("click", toggle);
  closeBtn?.addEventListener("click", toggle);

  function addMessage(text, role) {
    const time = new Date().toLocaleTimeString("az", {
      hour: "2-digit",
      minute: "2-digit",
    });

    const div = document.createElement("div");
    div.className = `ai-msg ai-msg-${role}`;
    const escaped = String(text)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/\n/g, "<br>");
    div.innerHTML = `
      <div class="ai-msg-bubble">${escaped}</div>
      <div class="ai-msg-time">${time}</div>
    `;

    messages?.querySelector(".ai-quick-btns")?.remove();

    messages?.appendChild(div);
    messages?.scrollTo({ top: messages.scrollHeight, behavior: "smooth" });
  }

  function showTyping() {
    const div = document.createElement("div");
    div.className = "ai-msg ai-msg-bot ai-typing";
    div.id = "aiTyping";
    div.innerHTML = `
      <div class="ai-msg-bubble">
        <div class="ai-typing-dots">
          <span></span><span></span><span></span>
        </div>
      </div>
    `;
    messages?.appendChild(div);
    messages?.scrollTo({ top: messages.scrollHeight, behavior: "smooth" });
  }

  function hideTyping() {
    document.getElementById("aiTyping")?.remove();
  }

  async function sendMessage(text) {
    const msg = text || input?.value?.trim();
    if (!msg || isLoading) return;

    if (input) input.value = "";
    isLoading = true;
    if (sendBtn) sendBtn.disabled = true;

    addMessage(msg, "user");
    history.push({ role: "user", content: msg });

    showTyping();

    try {
      const res = await fetch("/api/ai-chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          message: msg,
          history,
          lang: document.documentElement.lang || "az",
        }),
      });

      const data = await res.json();
      hideTyping();

      const reply = data.reply || "Bağışlayın, cavab verə bilmədim.";
      addMessage(reply, "bot");
      history.push({ role: "assistant", content: reply });
    } catch {
      hideTyping();
      addMessage("Bağlantı xətası. Zəhmət olmasa yenidən cəhd edin.", "bot");
    } finally {
      isLoading = false;
      if (sendBtn) sendBtn.disabled = false;
      input?.focus();
    }
  }

  messages?.addEventListener("click", (e) => {
    if (e.target.classList.contains("ai-quick-btn")) {
      sendMessage(e.target.dataset.msg);
    }
  });

  input?.addEventListener("keydown", (e) => {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });

  sendBtn?.addEventListener("click", () => sendMessage());

  if (!sessionStorage.getItem("aiChatOpened")) {
    setTimeout(() => {
      if (!isOpen) {
        toggle();
        sessionStorage.setItem("aiChatOpened", "1");
      }
    }, 45000);
  }
})();
