<div class="container mt-5">
    <h2>Xác thực mã OTP</h2>

    <?php if (isset($data['message'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($data['message']) ?></div>
    <?php endif; ?>
    <?php if (isset($data['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
    <?php endif; ?>

    <p>Một mã OTP đã được gửi đến email <strong><?= htmlspecialchars($data['email'] ?? '') ?></strong>. Vui lòng nhập mã vào ô bên dưới để đặt lại mật khẩu.</p>

    <form action="<?php echo APP_URL; ?>/AuthController/verifyResetOtp" method="POST">
        <div class="mb-3">
            <label for="otp" class="form-label">Mã OTP</label>
            <input type="text" class="form-control" id="otp" name="otp" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary">Xác nhận</button>
    </form>

    <div class="mt-3">
        <a href="<?php echo APP_URL; ?>/AuthController/forgotPassword" class="btn btn-link">Yêu cầu gửi lại mã</a>
    </div>
</div>