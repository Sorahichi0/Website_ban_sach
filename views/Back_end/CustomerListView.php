<div class="container mt-5">
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h3 class="text-primary"><i class="bi bi-people-fill"></i> Quản lý khách hàng</h3>
            </div>
        </div>

        <!-- Tìm kiếm -->
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="<?= APP_URL ?>/CustomerController/show" method="get" class="d-flex">
                    <input type="search" name="phone_search" class="form-control me-2" placeholder="Tìm theo số điện thoại" value="<?= htmlspecialchars($data['searchKeyword'] ?? '') ?>">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </form>
            </div>
        </div>

        <!-- Thông báo -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                    switch ($_GET['message']) {
                        case 'update_success': echo 'Cập nhật khách hàng thành công!'; break;
                        case 'delete_success': echo 'Xóa khách hàng thành công!'; break;
                        case 'update_error': echo 'Cập nhật khách hàng thất bại!'; break;
                        case 'delete_error': echo 'Xóa khách hàng thất bại!'; break;
                    }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Bảng danh sách khách hàng -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Họ và tên</th>
                                        <th>Số điện thoại</th>
                                        <th>Thống kê</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['customers'])): ?>
    <?php foreach ($data['customers'] as $customer): ?>
        <tr>
            <td>
                <strong><?= htmlspecialchars($customer['fullname']) ?></strong><br>
                <small class="text-muted"><?= htmlspecialchars($customer['email']) ?></small>
            </td>
            <td><?= htmlspecialchars($customer['phone']) ?></td>
            <td>
                <small>Lượt mua: <span class="fw-bold"><?= $customer['order_count'] ?></span></small><br>
                <small>Tổng chi: <span class="fw-bold text-danger"><?= number_format($customer['total_spent']) ?> ₫</span></small>
            </td>
            <td>
                <?php if ($customer['is_active']): ?>
                    <span class="badge bg-success">Hoạt động</span>
                <?php else: ?>
                    <span class="badge bg-danger">Đã khóa</span>
                <?php endif; ?>
            </td>
            <td>
                <a href="<?= APP_URL ?>/CustomerController/edit/<?= $customer['user_id'] ?>" class="btn btn-sm btn-outline-primary">Sửa</a>
                <?php if ($customer['is_active']): ?>
                    <a href="<?= APP_URL ?>/CustomerController/toggleStatus/<?= $customer['user_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc muốn khóa tài khoản này?');">Khóa</a>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/CustomerController/toggleStatus/<?= $customer['user_id'] ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Bạn có chắc muốn mở khóa tài khoản này?');">Mở khóa</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="5" class="text-center">Không tìm thấy khách hàng nào.</td>
    </tr>
<?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Nếu muốn: thêm số lượng khách hàng -->
                        <p class="mt-2">Tổng số khách hàng: <?= isset($data['customers']) && is_array($data['customers']) ? count($data['customers']) : 0 ?></p>
                    </div>
                </div>
            </div>
        </div>
</div>
