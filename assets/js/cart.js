document.querySelectorAll(".add-cart").forEach(btn => {
  btn.onclick = e => {
    e.preventDefault();
    fetch(btn.href)
      .then(() => location.reload());
  };
});
