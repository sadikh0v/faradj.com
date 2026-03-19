// Custom confirm modal
window.customConfirm = function(title, text, onConfirm) {
  var modal = document.getElementById("confirmModal");
  if (!modal) return;
  document.getElementById("confirmTitle").textContent = title || "Silmək istəyirsiniz?";
  document.getElementById("confirmText").textContent = text || "";
  modal.style.display = "flex";

  var yes = document.getElementById("confirmYes");
  var no = document.getElementById("confirmNo");

  var close = function() { modal.style.display = "none"; };

  yes.onclick = function() { close(); if (onConfirm) onConfirm(); };
  no.onclick = close;
  modal.onclick = function(e) { if (e.target === modal) close(); };
};

document.addEventListener("click", function(e) {
  var galleryBtn = e.target.closest(".gallery-delete-btn");
  if (galleryBtn) {
    e.preventDefault();
    var file = galleryBtn.dataset.file;
    var dir = galleryBtn.dataset.dir;
    var isUsed = galleryBtn.dataset.used === "1";
    var title = isUsed ? "⚠️ Diqqət! Bu şəkil istifadə edilir!" : "Şəkli silmək istəyirsiniz?";
    var text = isUsed ? file + " — silinsə, məlumatdan silinəcək." : file;
    customConfirm(title, text, function() {
      fetch("/admin/gallery/delete", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "file=" + encodeURIComponent(file) + "&dir=" + encodeURIComponent(dir)
      })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        if (data.success) {
          galleryBtn.closest(".gallery-item").remove();
        }
      });
    });
    return;
  }
  var delBtn = e.target.closest(".btn-delete-brand, .btn-delete-client");
  if (delBtn) {
    e.preventDefault();
    var form = delBtn.closest("form");
    if (form) {
      customConfirm("Silmək istəyirsiniz?", delBtn.dataset.title || "", function() {
        form.submit();
      });
    }
    return;
  }
  var btn = e.target.closest(".copy-btn");
  if (!btn) return;
  var text = btn.dataset.copy || "";
  navigator.clipboard.writeText(text).then(function() {
    var orig = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check" style="color:#00b894"></i> Kopyalandı';
    setTimeout(function() { btn.innerHTML = orig; }, 2000);
  });
});

// Галерея — массовое удаление
(function() {
  var toolbar = document.getElementById("galleryToolbar");
  if (!toolbar) return;

  var selectAll = document.getElementById("selectAll");
  var selectedCount = document.getElementById("selectedCount");
  var deleteBtn = document.getElementById("deleteSelectedBtn");

  function updateToolbar() {
    var checked = document.querySelectorAll(".gallery-checkbox:checked");
    var count = checked.length;
    selectedCount.textContent = count + " seçilib";
    if (count > 0) {
      toolbar.style.display = "flex";
    } else {
      toolbar.style.display = "none";
    }
  }

  document.addEventListener("change", function(e) {
    if (e.target.classList.contains("gallery-checkbox")) {
      updateToolbar();
    }
    if (e.target.id === "selectAll") {
      document.querySelectorAll(".gallery-checkbox").forEach(function(cb) {
        cb.checked = e.target.checked;
      });
      updateToolbar();
    }
  });

  if (deleteBtn) {
    deleteBtn.addEventListener("click", function() {
      var checked = document.querySelectorAll(".gallery-checkbox:checked");
      if (!checked.length) return;

      customConfirm(
        checked.length + " şəkli silmək istəyirsiniz?",
        "Bu əməliyyat geri qaytarıla bilməz.",
        function() {
          var promises = Array.from(checked).map(function(cb) {
            return fetch("/admin/gallery/delete", {
              method: "POST",
              headers: {"Content-Type": "application/x-www-form-urlencoded"},
              body: "file=" + encodeURIComponent(cb.value) + "&dir=" + encodeURIComponent(cb.dataset.dir)
            }).then(function(r) { return r.json(); }).then(function(data) {
              if (data.success) {
                cb.closest(".gallery-item").remove();
              }
            });
          });
          Promise.all(promises).then(function() {
            updateToolbar();
          });
        }
      );
    });
  }
})();

document.addEventListener("DOMContentLoaded", function() {
  var sidebar = document.querySelector(".admin-sidebar");
  var overlay = document.getElementById("sidebarOverlay");
  var burger = document.getElementById("adminBurger");
  var sidebarToggle = document.getElementById("sidebarToggle");

  function toggleSidebar() {
    if (sidebar) sidebar.classList.toggle("open");
    if (overlay) overlay.classList.toggle("open");
  }
  function closeSidebar() {
    if (sidebar) sidebar.classList.remove("open");
    if (overlay) overlay.classList.remove("open");
  }

  if (burger) burger.addEventListener("click", toggleSidebar);
  if (overlay) overlay.addEventListener("click", closeSidebar);
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", toggleSidebar);
  }

  // Legacy modal (old admin.php)
  var modal = document.getElementById("eventModal");
  var btnAdd = document.getElementById("btnAddEvent");
  var btnCancel = document.getElementById("btnCancelModal");
  var form = document.getElementById("eventForm");
  var modalTitle = document.getElementById("modalTitle");
  if (modal && form) {
    function openModal(eventData) {
      form.reset();
      if (document.getElementById("eventId")) document.getElementById("eventId").value = "";
      if (document.getElementById("is_published")) document.getElementById("is_published").checked = true;
      if (eventData) {
        modalTitle.textContent = "Düzəlt";
        if (document.getElementById("eventId")) document.getElementById("eventId").value = eventData.id;
        if (document.getElementById("title")) document.getElementById("title").value = eventData.title || "";
        if (document.getElementById("excerpt")) document.getElementById("excerpt").value = eventData.excerpt || "";
        if (document.getElementById("full_text")) document.getElementById("full_text").value = eventData.full_text || "";
        if (document.getElementById("category")) document.getElementById("category").value = eventData.category || "xebərlər";
        if (document.getElementById("author")) document.getElementById("author").value = eventData.author || "";
        if (document.getElementById("event_date")) document.getElementById("event_date").value = eventData.event_date || "";
        if (document.getElementById("image_url")) document.getElementById("image_url").value = eventData.image_url || "";
        if (document.getElementById("is_published")) document.getElementById("is_published").checked = !!eventData.is_published;
      } else {
        modalTitle.textContent = "Yeni xəbər/tədbir";
      }
      modal.classList.remove("hidden");
      document.body.style.overflow = "hidden";
    }
    function closeModal() {
      modal.classList.add("hidden");
      document.body.style.overflow = "";
      if (window.__editEvent) window.location.href = "/admin.php";
    }
    if (btnAdd) btnAdd.addEventListener("click", function() { openModal(null); });
    if (btnCancel) btnCancel.addEventListener("click", closeModal);
    modal.addEventListener("click", function(e) { if (e.target === modal) closeModal(); });
    if (window.__editEvent) openModal(window.__editEvent);
  }
});
