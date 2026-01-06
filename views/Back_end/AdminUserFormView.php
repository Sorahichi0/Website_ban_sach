<?php
$isEdit = isset($data['editItem']);
$editItem = $isEdit ? $data['editItem'] : null;
$formAction = $isEdit ? APP_URL . "/AdminUserController/edit/" . $editItem['user_id'] : APP_URL . "/AdminUserController/create";
$roles = $data['roles'] ?? [];
?>

<div class="container mt-5">
    <h2 class="text-primary mb-4"><?= $isEdit ? 'Chỉnh sửa Tài khoản' : 'Tạo mới Tài khoản Quản trị' ?></h2>

    <form action="<?= $formAction ?>" method="POST" class="card p-4 shadow-sm">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="fullname" class="form-label fw-bold">Họ và tên</label>
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?= htmlspecialchars($editItem['fullname'] ?? '') ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($editItem['email'] ?? '') ?>" <?= $isEdit ? 'readonly' : 'required' ?>>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label fw-bold">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" <?= !$isEdit ? 'required' : '' ?>>
                <?php if ($isEdit): ?>
                    <small class="form-text text-muted">Để trống nếu không muốn thay đổi mật khẩu.</small>
                <?php endif; ?>
            </div>
            <div class="col-md-6 mb-3">
                <label for="role_id" class="form-label fw-bold">Vai trò</label>
                <select name="role_id" id="role_id" class="form-select" required>
                    <option value="">-- Chọn vai trò --</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= ($editItem['role_id'] ?? '') == $role['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($role['role_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <?php if ($isEdit): ?>
        <div class="row">
             <div class="col-md-6 mb-3">
                <label for="phone" class="form-label fw-bold">Số điện thoại</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($editItem['phone'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="address" class="form-label fw-bold">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($editItem['address'] ?? '') ?>">
            </div>
        </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between mt-3">
            <a href="<?= APP_URL ?>/AdminUserController/show" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> <?= $isEdit ? 'Lưu thay đổi' : 'Tạo mới' ?>
            </button>
        </div>
    </form>
</div>