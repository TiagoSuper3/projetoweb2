(() => {
  const form = document.querySelector(".filters");
  const area = document.getElementById("products-area");

  if (!form || !area) {
    return;
  }

  /* remove paginação fallback (PHP) */
  const fallback = document.querySelector(".pagination-fallback");
  if (fallback) fallback.remove();

  function loadProducts(page = 1) {
    const params = new URLSearchParams(new FormData(form));
    params.set("page", page);

    fetch("ajax/produtos.php?" + params)
      .then(r => r.json())
      .then(data => {
        area.innerHTML = `
          <div class="grid">${data.products}</div>
          ${data.pagination}
        `;
      })
      .catch(err => console.error(err));
  }

  /* filtros */
  form.addEventListener("input", () => {
    loadProducts(1);
  });

  /* paginação (delegação) */
  area.addEventListener("click", e => {
    const btn = e.target.closest(".page-btn");
    if (!btn) return;

    e.preventDefault();
    loadProducts(btn.dataset.page);
  });

  loadProducts();

})();
