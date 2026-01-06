<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title><?= htmlspecialchars($data["post"]["title"] ?? "Chi tiết bài viết") ?></title>

    <!-- Bootstrap -->
    <link href="<?= APP_URL ?>/public/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script defer src="<?= APP_URL ?>/public/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- 🔹 NAVBAR -->
    <header>
        <nav class="navbar navbar-expand-sm navbar-light bg-light shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="<?= APP_URL ?>/Home/">BookStore</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                        aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/Home/">Trang chủ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active text-primary" href="<?= APP_URL ?>/Post/index">Tin tức</a>
                        </li>        
                        </li>
                    </ul>

                    <form class="d-flex me-3">
                        <input class="form-control me-2" type="search" placeholder="Tìm kiếm..." aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>

                    <?php if (isset($_SESSION['user'])): ?>
                        <span class="navbar-text me-2">👤 <?= htmlspecialchars($_SESSION['user']['fullname']) ?></span>
                        <a href="<?= APP_URL ?>/AuthController/logout" class="btn btn-outline-danger btn-sm me-2">Đăng xuất</a>
                        <a href="<?= APP_URL ?>/Home/orderHistory" class="btn btn-outline-primary btn-sm me-2">Lịch sử đơn hàng</a>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/AuthController/ShowLogin" class="btn btn-outline-success btn-sm me-2">Đăng nhập</a>
                    <?php endif; ?>

                    <a href="<?= APP_URL ?>/CartController/index" class="btn btn-warning btn-sm">
                        🛒 Giỏ hàng
                        <?php if (!empty($_SESSION['cart'])): ?>
                            <span class="badge bg-danger"><?= count($_SESSION['cart']); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- 🔹 NỘI DUNG CHÍNH -->
    <main class="container my-5">
        <?php if (!empty($data["post"])): ?>
            <div class="card shadow border-0">
                <?php if (!empty($data["post"]["image"])): ?>
                    <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($data["post"]["image"]) ?>" 
                         alt="<?= htmlspecialchars($data["post"]["title"]) ?>"
                         class="card-img-top" 
                         style="max-height: 400px; object-fit: cover;">
                <?php endif; ?>

                <div class="card-body p-4">
                    <h2 class="card-title text-primary mb-3"><?= htmlspecialchars($data["post"]["title"]) ?></h2>
                    <p class="text-muted mb-4">
                        <i class="bi bi-clock"></i> 
                        Ngày đăng: <?= date('d/m/Y H:i', strtotime($data["post"]["created_at"])) ?>
                    </p>
                    <div class="card-text fs-5" style="line-height: 1.7;">
                        <?= nl2br($data["post"]["content"]) ?>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="<?= APP_URL ?>/Post/index" class="btn btn-outline-secondary">
                    ⬅ Quay lại danh sách tin tức
                </a>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                Bài viết không tồn tại hoặc đã bị xóa.
            </div>
        <?php endif; ?>
    </main>

    <!-- 🔹 FOOTER -->
    <footer class="bg-light text-center py-3 mt-5 border-top">
        <p class="mb-0 text-muted">&copy; <?= date('Y') ?> MyWebsite - Tôi bị NGÔ🌽🌽🌽  </p>
    </footer>
</body>
</html>
