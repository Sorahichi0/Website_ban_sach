<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Quản lý Nhà cung cấp</h2>
        <a href="<?= APP_URL ?>/SupplierController/create" class="btn btn-success fw-bold">
            <i class="bi bi-plus-circle"></i> Thêm NCC
        </a>
    </div>

    <!-- Form tìm kiếm -->
    <div class="mb-4">
        <form action="<?= APP_URL ?>/SupplierController/show" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm theo tên, email, SĐT..." value="<?= htmlspecialchars($data['searchTerm'] ?? '') ?>">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Tìm
            </button>
            <a href="<?= APP_URL ?>/SupplierController/show" class="btn btn-secondary ms-2">Làm mới</a>
        </form>
    </div>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên NCC</th>
                <th>Người liên hệ</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Địa chỉ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['suppliers'])): foreach ($data['suppliers'] as $supplier): ?>
            <tr>
                <td><?= $supplier['id'] ?></td>
                <td><strong><?= htmlspecialchars($supplier['name']) ?></strong></td>
                <td><?= htmlspecialchars($supplier['contact_person']) ?></td>
                <td><?= htmlspecialchars($supplier['email']) ?></td>
                <td><?= htmlspecialchars($supplier['phone']) ?></td>
                <td><?= htmlspecialchars($supplier['address']) ?></td>
                <td>
                    <a href="<?= APP_URL ?>/SupplierController/edit/<?= $supplier['id'] ?>" class="btn btn-warning btn-sm">✏️ Sửa</a>
                    <a href="<?= APP_URL ?>/SupplierController/delete/<?= $supplier['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xoá nhà cung cấp này?');">🗑️ Xoá</a>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="7" class="text-center">Chưa có nhà cung cấp nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>