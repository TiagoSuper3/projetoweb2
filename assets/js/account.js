(() => {
  const menu = document.querySelector(".account-menu");
  if (!menu) return;

  const btn = menu.querySelector(".account-btn");
  const dropdown = menu.querySelector(".account-dropdown");

  btn.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    menu.classList.toggle("open");
  });

  // clicar fora fecha
  document.addEventListener("click", () => {
    menu.classList.remove("open");
  });

  // clicar dentro não fecha (para poderes clicar nos links)
  dropdown.addEventListener("click", (e) => e.stopPropagation());
})();
