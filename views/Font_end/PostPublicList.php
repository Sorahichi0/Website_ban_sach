<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Tin tức</title>

    <!-- Bootstrap CSS -->
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
                        
                    </ul>

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

    <!-- 🔹 MAIN CONTENT -->
    <main class="container mt-5">
        <h2 class="text-center mb-4 text-primary">📰 Danh sách bài viết</h2>

        <!-- Search Form -->
        <form action="<?= APP_URL ?>/Post/index" method="GET" class="mb-4">
            <div class="input-group">
                <input type="search" class="form-control" name="keyword" placeholder="Tìm kiếm bài viết theo tiêu đề..." value="<?= htmlspecialchars($data['keyword'] ?? '') ?>">
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSearch" aria-expanded="false" aria-controls="advancedSearch">
                    <i class="bi bi-sliders"></i> Nâng cao
                </button>
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Tìm kiếm</button>
            </div>

            <div class="collapse mt-3" id="advancedSearch">
                <div class="card card-body bg-light border-0">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="sort" class="form-label">Sắp xếp theo</label>
                            <select id="sort" name="sort" class="form-select">
                                <option value="newest" <?= (($data['sort'] ?? 'newest') == 'newest') ? 'selected' : '' ?>>Mới nhất</option>
                                <option value="oldest" <?= (($data['sort'] ?? '') == 'oldest') ? 'selected' : '' ?>>Cũ nhất</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row g-4">
            <?php if (!empty($data["posts"])): ?>
                <?php foreach ($data["posts"] as $post): ?>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 h-100">
                            <?php if (!empty($post["image"])): ?>
                                <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($post["image"]) ?>" 
                                     alt="<?= htmlspecialchars($post["title"]) ?>"
                                     class="card-img-top"
                                     style="height: 220px; width: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                     style="height: 220px; color: #999;">
                                     <i class="bi bi-image" style="font-size: 2rem;"></i>
                                </div>
                            <?php endif; ?>

                            <div class="card-body">
                                <h5 class="card-title text-truncate"><?= htmlspecialchars($post["title"]) ?></h5>
                                <p class="card-text text-muted" style="height: 60px; overflow: hidden;">
                                    <?= htmlspecialchars(mb_substr(strip_tags($post["content"]), 0, 100)) ?>...
                                </p>
                                <a href="<?= APP_URL ?>/Post/detail/<?= $post["id"] ?>" 
                                   class="btn btn-outline-primary btn-sm w-100">
                                    Đọc chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted">
                    <p>Hiện chưa có bài viết nào để hiển thị.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- 🔹 FOOTER -->
    <footer class="bg-light text-center py-3 mt-5 border-top">
        <p class="mb-0 text-muted">&copy; <?= date('Y') ?> MyWebsite - Tôi bị NGÔ🌽🌽🌽</p>
    </footer>
</body>
</html>
