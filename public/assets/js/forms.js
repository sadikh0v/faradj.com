(function() {
  "use strict";

  function showToast(title, message, type, duration) {
    type = type || "info";
    duration = duration || 4000;
    let container = document.querySelector(".toast-container");
    if (!container) {
      container = document.createElement("div");
      container.className = "toast-container";
      document.body.appendChild(container);
    }
    const icons = { success: "✅", error: "❌", info: "ℹ️", warning: "⚠️" };
    const toast = document.createElement("div");
    toast.className = "toast " + type;
    toast.innerHTML =
      '<span class="toast-icon">' + (icons[type] || "ℹ️") + "</span>" +
      '<div class="toast-body">' +
      '<div class="toast-title">' + (title || "") + "</div>" +
      (message ? '<div class="toast-msg">' + message + "</div>" : "") +
      "</div>" +
      '<button class="toast-close" type="button">×</button>';
    toast.querySelector(".toast-close").onclick = function() {
      toast.style.animation = "toastOut 0.35s ease forwards";
      setTimeout(function() { toast.remove(); }, 350);
    };
    container.appendChild(toast);
    setTimeout(function() {
      toast.style.animation = "toastOut 0.35s ease forwards";
      setTimeout(function() { toast.remove(); }, 350);
    }, duration);
  }
  window.showToast = showToast;

  const validators = {
    required: function(val) {
      return (val && String(val).trim() !== "") || "Bu sahə mütləqdir";
    },
    email: function(val) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(val || "")) || "Düzgün e-mail daxil edin";
    },
    phone: function(val) {
      return /^[\+]?[\d\s\-\(\)]{7,}$/.test(String(val || "").trim()) || "Düzgün telefon nömrəsi daxil edin";
    },
    minLength: function(min) {
      return function(val) {
        return (String(val || "").trim().length >= min) || ("Minimum " + min + " simvol daxil edin");
      };
    },
    maxLength: function(max) {
      return function(val) {
        return (String(val || "").trim().length <= max) || ("Maksimum " + max + " simvol");
      };
    }
  };

  function showError(field, msg) {
    field.classList.add("error");
    const err = field.closest(".field-input-wrap")?.querySelector(".field-error-msg");
    if (err) {
      err.textContent = msg;
      err.classList.add("visible");
    }
  }

  function hideError(field) {
    field.classList.remove("error");
    const err = field.closest(".field-input-wrap")?.querySelector(".field-error-msg");
    if (err) {
      err.textContent = "";
      err.classList.remove("visible");
    }
  }

  function validateField(input, rules) {
    const field = input ? input.closest(".form-field") : null;
    if (!field) return true;
    const val = input.value || "";
    let errorMsg = null;
    for (let i = 0; i < rules.length; i++) {
      const rule = rules[i];
      const result = typeof rule === "function" ? rule(val) : rule;
      if (result !== true) {
        errorMsg = result;
        break;
      }
    }
    const wrap = input.closest(".field-input-wrap");
    if (wrap) {
      if (errorMsg) {
        showError(input, errorMsg);
        return false;
      } else {
        hideError(input);
        return true;
      }
    }
    let msgEl = field.querySelector(".field-error-msg");
    if (!msgEl) {
      msgEl = document.createElement("span");
      msgEl.className = "field-error-msg";
      field.appendChild(msgEl);
    }
    if (errorMsg) {
      field.classList.add("error");
      field.classList.remove("success");
      msgEl.textContent = errorMsg;
      msgEl.style.display = "block";
      return false;
    } else {
      field.classList.remove("error");
      if (val.trim()) field.classList.add("success");
      msgEl.textContent = "";
      msgEl.style.display = "none";
      return true;
    }
  }

  function setupFormValidation(formId, fieldRules, formAction, onSuccess) {
    const form = document.getElementById(formId);
    if (!form) return;

    Object.keys(fieldRules).forEach(function(name) {
      const input = form.querySelector("[name=\"" + name + "\"]");
      if (!input) return;
      input.addEventListener("blur", function() {
        validateField(input, fieldRules[name]);
      });
      input.addEventListener("input", function() {
        const field = input.closest(".form-field");
        const hasError = field?.classList.contains("error") || input.classList.contains("error");
        if (hasError) validateField(input, fieldRules[name]);
      });
    });

    form.addEventListener("submit", function(e) {
      e.preventDefault();
      let isValid = true;
      Object.keys(fieldRules).forEach(function(name) {
        const input = form.querySelector("[name=\"" + name + "\"]");
        if (input && !validateField(input, fieldRules[name])) isValid = false;
      });
      if (!isValid) {
        showToast("Xəta", "Zəhmət olmasa bütün sahələri düzgün doldurun", "error");
        const firstError = form.querySelector(".form-field.error, .field-input-wrap input.error, .field-input-wrap select.error, .field-input-wrap textarea.error");
        const scrollEl = firstError ? (firstError.classList.contains("form-field") ? firstError : firstError.closest(".form-field")) : null;
        if (scrollEl) scrollEl.scrollIntoView({ behavior: "smooth", block: "center" });
        return;
      }

      const submitBtn = form.querySelector("[type=\"submit\"]");
      const originalText = submitBtn ? submitBtn.innerHTML : "";
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> Göndərilir...';
      }

      const fd = new FormData(form);
      fetch(formAction || form.action || window.location.pathname, {
        method: "POST",
        body: fd
      })
        .then(function(r) { return r.json(); })
        .then(function(data) {
          if (data.success) {
            if (data.redirect) {
              window.location.href = data.redirect;
              return;
            }
            if (data.message) {
              const f = document.getElementById(formId);
              if (f) {
                f.innerHTML = '<div style="text-align:center;padding:24px;">' +
                  '<i class="fas fa-check-circle" style="color:#00b894;font-size:48px;margin-bottom:12px;display:block;"></i>' +
                  '<p style="font-weight:600;font-size:16px;color:#1a1a2e;">' + data.message + '</p>' +
                  '</div>';
              }
              var modal = document.getElementById("callbackModal");
              if (modal) {
                setTimeout(function() { modal.classList.remove("active"); }, 2000);
              }
              if (onSuccess) onSuccess(data);
              return;
            }
            showToast("Uğurlu!", data.message || "Müraciətiniz qəbul edildi!", "success");
            form.reset();
            form.querySelectorAll(".form-field").forEach(function(f) {
              f.classList.remove("success", "error");
            });
            form.querySelectorAll(".field-input-wrap input, .field-input-wrap select, .field-input-wrap textarea").forEach(function(inp) {
              inp.classList.remove("error");
            });
            form.querySelectorAll(".field-error-msg").forEach(function(err) {
              err.textContent = "";
              err.classList.remove("visible");
            });
            if (typeof launchConfetti === "function") launchConfetti();
            if (onSuccess) onSuccess(data);
          } else {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = originalText;
            }
            if (data.error) {
              showToast("Xəta", data.error, "error");
            }
          }
        })
        .catch(function() {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
          }
          showToast("Bağlantı xətası", "Zəhmət olmasa internet bağlantınızı yoxlayın", "error");
        })
        .finally(function() {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
          }
        });
    });
  }

  document.addEventListener("DOMContentLoaded", function() {
    (function () {
      const form = document.getElementById("b2bForm");
      if (!form) return;

      const rules = {
        company:  { required: true, label: "Şirkətin adı" },
        contact:  { required: true, label: "Əlaqə şəxsi" },
        phone:    { required: true, label: "Telefon", pattern: /^\+?[\d\s\-()]{7,}$/ },
        email:    { required: true, label: "E-mail", pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/ },
        products: { required: true, label: "Hansı məhsullar lazımdır" },
      };

      function getError(field, rule) {
        const val = field.value.trim();
        if (rule.required && !val) return rule.label + " mütləqdir";
        if (rule.pattern && val && !rule.pattern.test(val)) {
          if (rule.pattern.toString().includes("@")) return "Düzgün e-mail daxil edin";
          return "Düzgün format daxil edin";
        }
        return null;
      }

      function showError(input, msg) {
        input.classList.add("error");
        const wrap = input.closest(".field-input-wrap");
        if (!wrap) return;
        const err = wrap.querySelector(".field-error-msg");
        if (err) {
          err.textContent = msg;
          err.removeAttribute("style");
          err.style.display = "block";
          err.style.color = "#e74c3c";
          err.style.fontSize = "12px";
          err.style.marginTop = "4px";
        }
      }

      function clearError(input) {
        input.classList.remove("error");
        const wrap = input.closest(".field-input-wrap");
        if (!wrap) return;
        const err = wrap.querySelector(".field-error-msg");
        if (err) {
          err.textContent = "";
          err.style.display = "none";
        }
      }

      Object.keys(rules).forEach(function (name) {
        const input = form.querySelector("[name=\"" + name + "\"]");
        if (!input) return;
        input.addEventListener("blur", function () {
          const msg = getError(input, rules[name]);
          msg ? showError(input, msg) : clearError(input);
        });
        input.addEventListener("input", function () {
          if (input.classList.contains("error")) {
            const msg = getError(input, rules[name]);
            msg ? showError(input, msg) : clearError(input);
          }
        });
      });

      form.addEventListener("submit", function (e) {
        e.preventDefault();
        let valid = true;
        let firstError = null;

        Object.keys(rules).forEach(function (name) {
          const input = form.querySelector("[name=\"" + name + "\"]");
          if (!input) return;
          const msg = getError(input, rules[name]);
          if (msg) {
            showError(input, msg);
            valid = false;
            if (!firstError) firstError = input;
          } else {
            clearError(input);
          }
        });

        if (!valid) {
          if (firstError) {
            firstError.scrollIntoView({ behavior: "smooth", block: "center" });
            firstError.focus();
          }
          return;
        }

        const btn = form.querySelector("[type=\"submit\"]");
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Göndərilir...';

        const data = new FormData(form);

        fetch("/b2b-submit.php", { method: "POST", body: data })
          .then(function (r) { return r.json(); })
          .then(function (res) {
            if (res.success) {
              window.location.href = res.redirect || "/thank-you?from=b2b";
            } else {
              btn.disabled = false;
              btn.innerHTML = origText;
              if (typeof showToast === "function") showToast("Xəta", res.error || res.message || "Xəta baş verdi", "error");
              else alert(res.error || res.message || "Xəta baş verdi");
            }
          })
          .catch(function () {
            btn.disabled = false;
            btn.innerHTML = origText;
            if (typeof showToast === "function") showToast("Bağlantı xətası", "Şəbəkə xətası. Yenidən cəhd edin.", "error");
            else alert("Şəbəkə xətası. Yenidən cəhd edin.");
          });
      });
    })();

    setupFormValidation("contactForm", {
      name: [validators.required, validators.minLength(2)],
      email: [validators.required, validators.email],
      subject: [validators.required],
      message: [validators.required, validators.minLength(10)]
    }, "/contact-submit.php", function() {
      window.location.href = "/thank-you?from=contact";
    });

    setupFormValidation("callbackForm", {
      name: [validators.required],
      phone: [validators.required, validators.phone]
    }, "/callback.php", function(data) {
      const form = document.getElementById("callbackForm");
      if (form && data.message) {
        form.innerHTML = '<div style="text-align:center;padding:20px;">' +
          '<i class="fas fa-check-circle" style="color:#00b894;font-size:48px;"></i>' +
          '<p style="margin-top:12px;font-weight:600;">' + data.message + '</p>' +
          '</div>';
      }
    });
  });
})();
