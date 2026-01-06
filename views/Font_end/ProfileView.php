<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Thông tin cá nhân</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($data['success'])): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($data['success']) ?></div>
                    <?php endif; ?>
                    <?php if (isset($data['error'])): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
                    <?php endif; ?>

                    <?php $user = $data['user']; ?>
                    <form action="<?= APP_URL ?>/AuthController/updateProfile" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                            <small class="form-text text-muted">Bạn không thể thay đổi địa chỉ email.</small>
                        </div>

                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                            <a href="<?= APP_URL ?>/AuthController/showChangePassword" class="btn btn-outline-secondary">Đổi mật khẩu</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>