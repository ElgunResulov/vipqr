<article class="product-item <?= empty($product['is_available']) ? 'is-unavailable' : '' ?>">
    <div class="product-media">
        <img src="<?= e(upload_url($product['image'] ?? null)) ?>"
             alt="<?= e($product['name']) ?>"
             width="176"
             height="176"
             loading="lazy"
             decoding="async">
        <?php if (empty($product['is_available'])): ?>
            <span class="stock-badge">Yoxdur</span>
        <?php endif; ?>
    </div>
    <div class="product-body">
        <h3 class="product-name"><?= e($product['name']) ?></h3>
        <?php if (!empty($product['description'])): ?>
            <p class="product-desc"><?= e($product['description']) ?></p>
        <?php endif; ?>
        <?php if (!empty($product['category_name']) && !empty($showCategoryLabel)): ?>
            <span class="product-cat"><?= e($product['category_name']) ?></span>
        <?php endif; ?>
    </div>
    <span class="product-price"><?= e(money_azn($product['price'])) ?></span>
</article>
