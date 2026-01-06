<!doctype html>
<html lang="vi">
<head>
    <title>Đăng nhập Quản trị</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link href="<?php echo APP_URL;?>/public/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 450px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card login-card shadow-lg">
            <div class="card-header text-center bg-primary text-white">
                <h3 class="mb-0">Đăng nhập Trang Quản trị</h3>
            </div>
            <div class="card-body p-4">
                <?php if (isset($data['error'])): ?>
                    <div class="alert alert-danger"><?= $data['error'] ?></div>
                <?php endif; ?>
                <form action="<?= APP_URL ?>/AdminAuthController/login" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold"><i class="bi bi-envelope-fill"></i> Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold"><i class="bi bi-key-fill"></i> Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Đăng nhập</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="<?= APP_URL ?>/Home">Về trang chủ</a>
            </div>
        </div>
    </div>
    <script src="<?php echo APP_URL;?>/public/js/bootstrap.bundle.min.js"></script>
</body>
</html>