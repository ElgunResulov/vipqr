<article class="product-item <?= empty($product['is_available']) ? 'is-unavailable' : '' ?> <?= !empty($product['is_featured']) || !empty($forceFeaturedBadge) ? 'is-featured' : '' ?> <?= !empty($product['is_popular']) || !empty($forcePopularBadge) ? 'is-popular' : '' ?>"
         <?= empty($skipProductAnchor) ? 'id="p-' . (int) ($product['id'] ?? 0) . '"' : '' ?>
         data-product-id="<?= (int) ($product['id'] ?? 0) ?>"
         data-product-name="<?= e(localized($product, 'name')) ?>"
         data-product-price="<?= e(money_azn($product['price'])) ?>">
    <div class="product-media">
        <?php if (!empty($popularRank)): ?>
            <span class="popular-rank" aria-hidden="true"><?= e(__('popular.rank', ['n' => (string) $popularRank])) ?></span>
        <?php endif; ?>
        <img src="<?= e(upload_url($product['image'] ?? null)) ?>"
             alt="<?= e(localized($product, 'name')) ?>"
             width="176"
             height="176"
             loading="lazy"
             decoding="async">
        <?php if (!empty($product['is_featured']) || !empty($forceFeaturedBadge)): ?>
            <span class="chef-badge"><?= e(__('featured.badge')) ?></span>
        <?php endif; ?>
        <?php if (empty($product['is_available'])): ?>
            <span class="stock-badge"><?= e(__('product.unavailable')) ?></span>
        <?php endif; ?>
    </div>
    <div class="product-body">
        <div class="product-row">
            <h3 class="product-name">
                <?= e(localized($product, 'name')) ?>
                <?php if (!empty($product['is_featured']) && empty($forceFeaturedBadge)): ?>
                    <span class="chef-pill"><?= e(__('featured.badge')) ?></span>
                <?php elseif (!empty($product['is_popular']) && empty($forcePopularBadge) && empty($forceFeaturedBadge)): ?>
                    <span class="popular-pill"><?= e(__('popular.badge')) ?></span>
                <?php endif; ?>
            </h3>
            <span class="product-dots" aria-hidden="true"></span>
            <span class="product-price"><?= e(money_azn($product['price'])) ?></span>
        </div>
        <?php
        $desc = localized($product, 'description');
        if ($desc !== ''):
        ?>
            <p class="product-desc"><?= e($desc) ?></p>
        <?php endif; ?>
        <?php if (!empty($showCategoryLabel)): ?>
            <?php
            $catForLang = [
                'name' => $product['category_name'] ?? '',
                'name_ru' => $product['category_name_ru'] ?? '',
                'name_en' => $product['category_name_en'] ?? '',
            ];
            ?>
            <span class="product-cat"><?= e(localized($catForLang, 'name')) ?></span>
        <?php endif; ?>
        <div class="product-actions">
            <button type="button"
                    class="product-action product-fav"
                    data-fav-toggle
                    aria-pressed="false"
                    aria-label="<?= e(__('fav.add')) ?>"
                    title="<?= e(__('fav.add')) ?>">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3.6l2.4 4.86 5.36.78-3.88 3.78.92 5.34L12 15.9l-4.8 2.52.92-5.34-3.88-3.78 5.36-.78L12 3.6z"/>
                </svg>
            </button>
            <button type="button"
                    class="product-action product-share"
                    data-share-product
                    aria-label="<?= e(__('share.product')) ?>"
                    title="<?= e(__('share.product')) ?>">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="18" cy="5" r="2.4"/>
                    <circle cx="6" cy="12" r="2.4"/>
                    <circle cx="18" cy="19" r="2.4"/>
                    <path d="M8.2 10.8l7.6-4.4M8.2 13.2l7.6 4.4"/>
                </svg>
            </button>
        </div>
    </div>
</article>
<?php
$forceFeaturedBadge = false;
$forcePopularBadge = false;
$popularRank = null;
$skipProductAnchor = false;
$showCategoryLabel = $showCategoryLabel ?? false;
?>
