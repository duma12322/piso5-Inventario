document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".toggle-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const targetId = btn.dataset.target;
      const div = document.getElementById(targetId);
      div.classList.toggle("d-none");
    });
  });
});
