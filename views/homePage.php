<!doctype html>
<html lang="en">
    <head>
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <link href="<?php echo APP_URL;?>/public/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
        <style>
            /* Custom CSS để dropdown mở khi hover trên màn hình lớn */
            @media (min-width: 992px) { /* Áp dụng từ breakpoint 'lg' trở lên */
                .navbar .nav-item.dropdown:hover .dropdown-menu {
                    display: block;
                }
                .navbar .nav-item.dropdown .dropdown-menu {
                    margin-top: 0; /* Đảm bảo menu không bị lệch */
                }
                /* Nếu có dropdown lồng nhau (cấp 2 có cấp 3), cần thêm CSS tương tự */
                /* .dropdown-menu .dropend:hover .dropdown-menu {
                    display: block;
                } */
            }
        </style>
        <!-- Bootstrap Bundle JS (có Popper) -->
        <script defer src="<?php echo APP_URL;?>/public/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <header>
            <!-- place navbar here -->
             <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container">
                    <a class="navbar-brand" href="<?= APP_URL ?>/Home/">BookStore</a>
                    <button 
                        class="navbar-toggler d-lg-none"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapsibleNavId"
                        aria-controls="collapsibleNavId"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="collapsibleNavId">
                        <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" href="<?php echo APP_URL;?>/Home/" aria-current="page">
                                    Trang chủ
                                    <span class="visually-hidden">(current)</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= APP_URL ?>/Post/index" class="nav-link">Tin tức</a>

                            </li>                           
                        </ul>
                        <!-- Form tìm kiếm -->
                        <form class="d-flex me-3" action="<?= APP_URL ?>/Home/advancedSearch" method="GET">
                            <input class="form-control me-2" type="search" name="tensp" placeholder="Tìm kiếm sách..." aria-label="Search" value="<?= htmlspecialchars($_GET['tensp'] ?? '') ?>">
                            <button class="btn btn-outline-success" type="submit">Tìm</button>
                        </form>
                        <!-- Vị trí của form tìm kiếm cũ -->
                        <?php if (isset($_SESSION['user'])): ?>
                            <div class="nav-item dropdown ms-3">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    👤 <?= htmlspecialchars($_SESSION['user']['fullname']) ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="<?= APP_URL ?>/AuthController/showProfile">Thông tin cá nhân</a></li>
                                    <li><a class="dropdown-item" href="<?= APP_URL ?>/AuthController/showChangePassword">Đổi mật khẩu</a></li>
                                    <li><a class="dropdown-item" href="<?= APP_URL ?>/Home/orderHistory">Lịch sử đơn hàng</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= APP_URL ?>/AuthController/logout">Đăng xuất</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo APP_URL; ?>/AuthController/Show" class="btn btn-primary ms-3">Đăng ký</a>
                            <a href="<?php echo APP_URL; ?>/AuthController/ShowLogin" class="btn btn-outline-success ms-2">Đăng nhập</a>
                        <?php endif; ?>

                        <!-- 🛒 Thêm giỏ hàng -->
                        <a href="<?= APP_URL ?>/CartController/index" class="btn btn-warning ms-2">
                            🛒 Giỏ hàng
                            <?php
                                $cartCount = 0;
                                if (isset($_SESSION['user']['id'])) {
                                    // Nếu đã đăng nhập, số lượng là số item trong $data['listProductOrder'] (nếu có)
                                    // Hoặc cần một cách khác để lấy count mà không cần load cả giỏ hàng
                                    // Tạm thời, chúng ta sẽ dựa vào dữ liệu đã có nếu trang là giỏ hàng
                                    if (isset($data['page']) && $data['page'] === 'OrderView' && isset($data['listProductOrder'])) {
                                        $cartCount = count($data['listProductOrder']);
                                    }
                                } else {
                                    $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                                }
                                if ($cartCount > 0) {
                                    echo '<span class="badge bg-danger">' . $cartCount . '</span>';
                                }
                            ?>
                        </a>
                    </div>
                </div>
             </nav>
             <!-- Thanh danh mục sách -->
             <?php if (!empty($data['categoryTree'])): ?>
             <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#categoryNavbar" aria-controls="categoryNavbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="categoryNavbar">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <?php foreach ($data['categoryTree'] as $level1): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link <?= !empty($level1['children']) ? 'dropdown-toggle' : '' ?>"
                                       href="<?= !empty($level1['children']) ? '#' : APP_URL . '/Home/category/' . htmlspecialchars($level1['maLoaiSP']) ?>"
                                       id="categoryDropdown<?= htmlspecialchars($level1['maLoaiSP']) ?>"
                                       role="button" <?= !empty($level1['children']) ? 'data-bs-toggle="dropdown" aria-expanded="false"' : '' ?>>
                                       <?= htmlspecialchars($level1['tenLoaiSP']) ?>
                                    </a>
                                    <?php if (!empty($level1['children'])): ?>
                                        <ul class="dropdown-menu" aria-labelledby="categoryDropdown<?= htmlspecialchars($level1['maLoaiSP']) ?>">
                                            <?php foreach ($level1['children'] as $level2): ?>
                                                <li><a class="dropdown-item" href="<?= APP_URL ?>/Home/category/<?= htmlspecialchars($level2['maLoaiSP']) ?>"><?= htmlspecialchars($level2['tenLoaiSP']) ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
             </nav>
             <?php endif; ?>
        </header>


        <main>
            <?php
              require_once "./views/Font_end/".$data["page"].".php";
            ?>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    </body>
</html>
