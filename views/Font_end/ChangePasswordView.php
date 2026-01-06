<div class="container mt-5">
    <h2>Đổi mật khẩu</h2>

    <?php if (isset($data['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($data['success']) ?></div>
    <?php endif; ?>
    <?php if (isset($data['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
    <?php endif; ?>

    <form action="<?php echo APP_URL; ?>/AuthController/changePassword" method="POST">
        <div class="mb-3">
            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">Mật khẩu mới</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
            <small class="form-text text-muted">Mật khẩu phải có ít nhất 6 ký tự.</small>
        </div>
        <div class="mb-3">
            <label for="confirm_new_password" class="form-label">Xác nhận mật khẩu mới</label>
            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
    </form>

    <div class="mt-4">
        <a href="<?= APP_URL ?>/AuthController/showProfile" class="btn btn-secondary">Quay lại thông tin cá nhân</a>
        <a href="<?= APP_URL ?>/Home" class="btn btn-outline-secondary">Quay lại trang chủ</a>
    </div>
</div>