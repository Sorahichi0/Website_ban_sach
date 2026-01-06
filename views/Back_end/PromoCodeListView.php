<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Quản lý Voucher</h2>
        <a href="<?= APP_URL ?>/PromoCodeController/create" class="btn btn-success fw-bold">
            <i class="bi bi-plus-circle"></i> Thêm mã mới
        </a>
    </div>

    <!-- Form tìm kiếm -->
    <div class="mb-4">
        <form action="<?= APP_URL ?>/PromoCodeController/show" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm theo mã voucher..." value="<?= htmlspecialchars($data['searchTerm'] ?? '') ?>">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Tìm
            </button>
            <a href="<?= APP_URL ?>/PromoCodeController/show" class="btn btn-secondary ms-2">Làm mới</a>
        </form>
    </div>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Mã</th>
                <th>Loại</th>
                <th>Giá trị / Đơn tối thiểu</th>
                <th>Sử dụng</th>
                <th>Ngày BĐ</th>
                <th>Ngày kết thúc</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['promo_codes'])): foreach ($data['promo_codes'] as $code): ?>
            <tr>
                <td><?= $code['id'] ?></td>
                <td><strong><?= htmlspecialchars($code['code']) ?></strong></td>
                <td><?= $code['type'] == 'percentage' ? 'Phần trăm' : 'Số tiền cố định' ?></td>
                <td>
                    <span class="fw-bold text-danger"><?= $code['type'] == 'percentage' ? $code['value'] . '%' : number_format($code['value']) . ' ₫' ?></span><br>
                    <small class="text-muted">Đơn từ: <?= number_format($code['min_order_value']) ?> ₫</small>
                </td>
                <td>
                    <?= $code['usage_count'] ?> / 
                    <?= $code['usage_limit'] > 0 ? $code['usage_limit'] : '∞' ?>
                </td>
                <td><?= date('d/m/y H:i', strtotime($code['start_date'])) ?></td>
                <td><?= date('d/m/y H:i', strtotime($code['end_date'])) ?></td>
                <td>
                    <span class="badge <?= $code['status'] ? 'bg-success' : 'bg-danger' ?>">
                        <?= $code['status'] ? 'Hoạt động' : 'Vô hiệu' ?>
                    </span>
                </td>
                <td>
                    <a href="<?= APP_URL ?>/PromoCodeController/edit/<?= $code['id'] ?>" class="btn btn-warning btn-sm">✏️ Sửa</a>
                    <a href="<?= APP_URL ?>/PromoCodeController/delete/<?= $code['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xoá voucher này?');">🗑️ Xoá</a>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="9" class="text-center">Chưa có voucher nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>