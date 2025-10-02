document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".faq-question").forEach(button => {
    button.addEventListener("click", () => {
      const answer = button.nextElementSibling;

      // cerrar otros abiertos (opcional)
      document.querySelectorAll(".faq-answer").forEach(a => {
        if (a !== answer) a.style.display = "none";
      });

      // alternar visibilidad
      answer.style.display = answer.style.display === "block" ? "none" : "block";
    });
  });
});
