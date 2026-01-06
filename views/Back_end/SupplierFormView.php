<?php
$isEdit = isset($data['editItem']);
$editItem = $isEdit ? $data['editItem'] : null;
$formAction = $isEdit ? APP_URL . "/SupplierController/edit/" . $editItem['id'] : APP_URL . "/SupplierController/create";
?>

<div class="container mt-5">
    <h2 class="text-primary mb-4"><?= $isEdit ? 'Chỉnh sửa Nhà cung cấp' : 'Tạo mới Nhà cung cấp' ?></h2>

    <form action="<?= $formAction ?>" method="POST" class="card p-4 shadow-sm">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label fw-bold">Tên Nhà cung cấp</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($editItem['name'] ?? '') ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="contact_person" class="form-label fw-bold">Người liên hệ</label>
                <input type="text" class="form-control" id="contact_person" name="contact_person" value="<?= htmlspecialchars($editItem['contact_person'] ?? '') ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($editItem['email'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label fw-bold">Số điện thoại</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($editItem['phone'] ?? '') ?>">
            </div>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label fw-bold">Địa chỉ</label>
            <textarea class="form-control" id="address" name="address" rows="2"><?= htmlspecialchars($editItem['address'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="contract_info" class="form-label fw-bold">Thông tin hợp đồng/Ghi chú</label>
            <textarea class="form-control" id="contract_info" name="contract_info" rows="3"><?= htmlspecialchars($editItem['contract_info'] ?? '') ?></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?= APP_URL ?>/SupplierController/show" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> <?= $isEdit ? 'Lưu thay đổi' : 'Tạo mới' ?>
            </button>
        </div>
    </form>
</div>