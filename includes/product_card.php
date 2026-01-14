<div class="card">
  <?php
  $image = $p['image'];

  if (!$image) {
    $src = 'assets/uploads/placeholder.png';
  } elseif (str_starts_with($image, 'http')) {
    $src = $image;
  } else {
    $src = 'assets/uploads/' . $image;
  }
  ?>

  <img
    src="<?= htmlspecialchars($src) ?>"
    class="product-img"
    loading="lazy"
    decoding="async"
    alt="<?= htmlspecialchars($p['name']) ?>"
  >

  <h3><?= htmlspecialchars($p['name']) ?></h3>

  <p class="price">
    <?= number_format((float)$p['price'], 2, ',', '.') ?> €
  </p>

  <p>Stock: <?= (int)$p['stock'] ?></p>

  <div class="row">
    <a class="btn secondary" href="produto.php?id=<?= (int)$p['id'] ?>">Ver</a>

    <?php if ((int)$p['stock'] > 0): ?>
      <form action="add_to_cart.php" method="post">
        <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
        <button class="btn" type="submit">Adicionar</button>
      </form>
    <?php else: ?>
      <span>Sem stock</span>
    <?php endif; ?>
  </div>
</div>
