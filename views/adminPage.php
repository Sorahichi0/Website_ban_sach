<!doctype html>
<html lang="vi">
<head>
    <title>Trang Quản Trị</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link href="<?php echo APP_URL;?>/public/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Bootstrap Bundle JS (có Popper) -->
    <script defer src="<?php echo APP_URL;?>/public/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>

<body>
    <header class="bg-dark text-white shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="<?= APP_URL ?>/Product/show">Trang Quản Trị</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="adminNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a href="<?= APP_URL ?>/Product/show" class="nav-link <?= in_array(($data['page'] ?? ''), ['ProductListView', 'ProductView']) ? 'active' : '' ?>">Sản phẩm</a></li>
                        <li class="nav-item"><a href="<?= APP_URL ?>/ProductType/show" class="nav-link <?= ($data['page'] ?? '') == 'ProductTypeView' ? 'active' : '' ?>">Loại sản phẩm</a></li>
                        <li class="nav-item"><a href="<?= APP_URL ?>/AdminOrderController/show" class="nav-link <?= in_array(($data['page'] ?? ''), ['AdminOrderListView', 'AdminOrderDetailView']) ? 'active' : '' ?>">Đơn hàng</a></li>
                        <li class="nav-item"><a href="<?= APP_URL ?>/CustomerController/show" class="nav-link <?= in_array(($data['page'] ?? ''), ['CustomerListView', 'CustomerEditView']) ? 'active' : '' ?>"><i class="bi bi-people-fill"></i> Khách hàng</a></li>
                        <li class="nav-item"><a href="<?= APP_URL ?>/SupplierController/show" class="nav-link <?= in_array(($data['page'] ?? ''), ['SupplierListView', 'SupplierFormView']) ? 'active' : '' ?>">Nhà cung cấp</a></li>
                        <li class="nav-item"><a href="<?= APP_URL ?>/PromoCodeController/show" class="nav-link <?= in_array(($data['page'] ?? ''), ['PromoCodeListView', 'PromoCodeFormView']) ? 'active' : '' ?>">Voucher</a></li>
                        <li class="nav-item"><a href="<?= APP_URL ?>/Post/show" class="nav-link <?= in_array(($data['page'] ?? ''), ['PostListView', 'PostCreate', 'PostEdit']) ? 'active' : '' ?>">Bài viết</a></li>
                        <li class="nav-item"><a href="<?= APP_URL ?>/CommentController/show" class="nav-link <?= ($data['page'] ?? '') == 'CommentListView' ? 'active' : '' ?>">Bình luận</a></li>
                        <li class="nav-item"><a href="<?= APP_URL ?>/Report/Show" class="nav-link <?= ($data['page'] ?? '') == 'ReportView' ? 'active' : '' ?>"><i class="bi bi-graph-up-arrow"></i> Báo cáo doanh thu</a></li>
                        <li class="nav-item"><a href="<?= APP_URL ?>/Report/inventory" class="nav-link <?= ($data['page'] ?? '') == 'InventoryReportView' ? 'active' : '' ?>"><i class="bi bi-box-seam"></i> Báo cáo tồn kho</a></li>
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role_name'] === 'SuperAdmin'): ?>
                            <li class="nav-item"><a href="<?= APP_URL ?>/AdminUserController/show" class="nav-link <?= in_array(($data['page'] ?? ''), ['AdminUserListView', 'AdminUserFormView']) ? 'active' : '' ?>"><i class="bi bi-person-badge"></i> Quản trị hệ thống</a></li>
                        <?php endif; ?>
                    </ul>
                    <div class="d-flex align-items-center">
                        <?php if(isset($_SESSION['user'])): ?>
                            <span class="navbar-text me-3">Chào, <?= htmlspecialchars($_SESSION['user']['fullname']) ?>!</span>
                        <?php endif; ?>
                        <a href="<?= APP_URL ?>/Home" class="btn btn-outline-info btn-sm me-2">Về trang chủ</a>
                        <a href="<?= APP_URL ?>/AdminAuthController/logout" class="btn btn-outline-danger btn-sm">Đăng xuất</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mt-4">
        <?php
          // Tải trang con được yêu cầu
          require_once "./views/Back_end/".$data["page"].".php";
        ?>
    </main>
</body>
</html>