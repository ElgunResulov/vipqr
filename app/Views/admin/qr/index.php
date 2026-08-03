<div class="row g-4">
    <div class="col-lg-6">
        <div class="bg-white border rounded-3 p-4 text-center">
            <div class="qr-preview mx-auto mb-3">
                <?= $qrSvg ?>
            </div>
            <p class="small text-muted mb-3">Bu QR kod public menyü URL-inə aparır.</p>
            <a class="btn btn-admin" href="<?= e(url('admin/qr/download')) ?>">PNG yüklə</a>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="bg-white border rounded-3 p-4">
            <h2 class="h6">Menyü URL</h2>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="menuUrl" value="<?= e($menuUrl) ?>" readonly>
                <button class="btn btn-outline-secondary" type="button" id="copyUrl">Kopyala</button>
            </div>
            <p class="mb-0 small text-muted">
                QR kodu çap edib masalara yerləşdirin. Müştərilər skan edərək menyünü açacaq.
            </p>
        </div>
    </div>
</div>
<script>
document.getElementById('copyUrl')?.addEventListener('click', async () => {
    const input = document.getElementById('menuUrl');
    try {
        await navigator.clipboard.writeText(input.value);
        alert('URL kopyalandı');
    } catch (e) {
        input.select();
        document.execCommand('copy');
    }
});
</script>
