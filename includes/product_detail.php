<!-- includes/product_detail.php -->
<div class="product-page">

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

  <div>
    <h1><?= htmlspecialchars($p['name']) ?></h1>

    <p class="price">
      <?= number_format((float)$p['price'], 2, ',', '.') ?> €
    </p>

    <p><?= nl2br(htmlspecialchars($p['description'] ?? '')) ?></p>

    <p><strong>Stock:</strong> <?= (int)$p['stock'] ?></p>

    <?php if ((int)$p['stock'] > 0): ?>
      <form action="add_to_cart.php" method="post" style="max-width:240px">
        <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">

        <label>Quantidade</label>
        <input class="input" type="number"
               name="qty"
               min="1"
               max="<?= (int)$p['stock'] ?>"
               value="1">

        <br><br>
        <button class="btn" type="submit">Adicionar ao carrinho</button>
      </form>
    <?php else: ?>
      <p class="notice">Sem stock.</p>
    <?php endif; ?>

    <p>
      <a class="btn secondary" href="produtos.php">Voltar ao catálogo</a>
    </p>
  </div>

</div>
