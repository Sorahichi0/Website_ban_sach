<div class="container mt-5">
    <h2>Đặt lại mật khẩu mới</h2>

    <?php if (isset($data['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
    <?php endif; ?>

    <form action="<?php echo APP_URL; ?>/AuthController/setNewPassword" method="POST">
        <div class="mb-3">
            <label for="new_password" class="form-label">Mật khẩu mới</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required autofocus>
            <small class="form-text text-muted">Mật khẩu phải có ít nhất 6 ký tự.</small>
        </div>
        <div class="mb-3">
            <label for="confirm_new_password" class="form-label">Xác nhận mật khẩu mới</label>
            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
    </form>
</div>