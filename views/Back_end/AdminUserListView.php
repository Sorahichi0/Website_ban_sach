<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary"><i class="bi bi-person-badge"></i> Quản lý Tài khoản Quản trị</h2>
        <a href="<?= APP_URL ?>/AdminUserController/create" class="btn btn-success fw-bold">
            <i class="bi bi-plus-circle"></i> Thêm tài khoản
        </a>
    </div>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['admins'])): foreach ($data['admins'] as $admin): ?>
            <tr>
                <td><?= $admin['user_id'] ?></td>
                <td><strong><?= htmlspecialchars($admin['fullname']) ?></strong></td>
                <td><?= htmlspecialchars($admin['email']) ?></td>
                <td>
                    <span class="badge bg-info text-dark"><?= htmlspecialchars($admin['role_name']) ?></span>
                </td>
                <td>
                    <?php if ($admin['is_active']): ?>
                        <span class="badge bg-success">Hoạt động</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Đã khóa</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?= APP_URL ?>/AdminUserController/edit/<?= $admin['user_id'] ?>" class="btn btn-warning btn-sm">✏️ Sửa</a>
                    <?php if ($admin['user_id'] != $_SESSION['user']['id']): // Không cho tự xóa mình ?>
                    <a href="<?= APP_URL ?>/AdminUserController/delete/<?= $admin['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xoá tài khoản này?');">🗑️ Xoá</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="6" class="text-center">Chưa có tài khoản quản trị nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>