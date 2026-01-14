const form = document.querySelector(".filters");
const grid = document.querySelector(".grid");

form.addEventListener("input", () => {
  const params = new URLSearchParams(new FormData(form));

  fetch("ajax/search_products.php?" + params)
    .then(r => r.json())
    .then(data => {
      grid.innerHTML = "";
      if (!data.length) {
        grid.innerHTML = "<p>Nenhum produto encontrado.</p>";
        return;
      }
      data.forEach(p => {
        grid.innerHTML += `
          <div class="card">
            <img src="assets/uploads/${p.image || 'placeholder.png'}">
            <h3>${p.name}</h3>
            <p class="price">${Number(p.price).toFixed(2)} €</p>
            <a class="btn" href="produto.php?id=${p.id}">Ver</a>
          </div>
        `;
      });
    });
});
