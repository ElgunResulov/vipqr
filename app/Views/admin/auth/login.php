<form method="post" action="<?= e(url('admin/login')) ?>" class="login-form">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label class="form-label" for="username">İstifadəçi adı və ya e-poçt</label>
        <input class="form-control" type="text" id="username" name="username" value="<?= e(old('username')) ?>" required autofocus>
    </div>
    <div class="mb-4">
        <label class="form-label" for="password">Şifrə</label>
        <input class="form-control" type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-admin w-100">Daxil ol</button>
</form>
