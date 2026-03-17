document.addEventListener("DOMContentLoaded", () => {
  // Contact form handled by forms.js

  // FAQ Accordion
  document.querySelectorAll(".faq-item").forEach((item) => {
    const question = item.querySelector(".faq-question");
    question?.addEventListener("click", () => {
      item.classList.toggle("open");
    });
  });
});
