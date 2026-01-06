<div class="container mt-5">
    <h2>Quên mật khẩu</h2>

    <?php if (isset($data['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
    <?php endif; ?>

    <p>Vui lòng nhập địa chỉ email của bạn. Chúng tôi sẽ gửi một mã OTP để bạn có thể đặt lại mật khẩu.</p>
    <form action="<?php echo APP_URL; ?>/AuthController/requestPasswordResetOtp" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Gửi mã OTP</button>
    </form>
    <div class="mt-3">
        <a href="<?php echo APP_URL; ?>/AuthController/ShowLogin" class="btn btn-link">Đăng nhập</a>
    </div>
</div>
