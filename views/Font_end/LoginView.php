<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="/public/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Đăng nhập tài khoản</h2>

    <?php if (isset($data['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($data['success']) ?></div>
    <?php endif; ?>
    <?php if (isset($data['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
    <?php endif; ?>

    <form action="<?php echo APP_URL; ?>/AuthController/login" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-success">Đăng nhập</button>
    <a href="<?php echo APP_URL; ?>/AuthController/Show" class="btn btn-primary ms-2">Đăng ký thành viên</a>
    <a href="<?php echo APP_URL; ?>/AuthController/forgotPassword" class="btn btn-link ms-2">Quên mật khẩu?</a>
    </form>
</div>

</body>
</html>
