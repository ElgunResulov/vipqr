<div class="container py-5">
    <div class="error-panel text-center mx-auto">
        <p class="error-code">500</p>
        <h1><?= e(__('error.500_title')) ?></h1>
        <p><?= e(__('error.500_text')) ?></p>
        <a class="btn btn-hero" href="<?= e(url()) ?>"><?= e(__('error.back_home')) ?></a>
    </div>
</div>
