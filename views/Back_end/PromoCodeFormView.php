<?php
$isEdit = isset($data['editItem']);
$editItem = $isEdit ? $data['editItem'] : null;
$formAction = $isEdit ? APP_URL . "/PromoCodeController/edit/" . $editItem['id'] : APP_URL . "/PromoCodeController/create";
?>

<div class="container mt-5">
    <h2 class="text-primary mb-4"><?= $isEdit ? 'Chỉnh sửa Voucher' : 'Tạo mới Voucher' ?></h2>

    <form action="<?= $formAction ?>" method="POST" class="card p-4 shadow-sm">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="code" class="form-label fw-bold">Mã Voucher</label>
                <input type="text" class="form-control" id="code" name="code" value="<?= htmlspecialchars($editItem['code'] ?? '') ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="type" class="form-label fw-bold">Loại Voucher</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="percentage" <?= ($editItem['type'] ?? '') == 'percentage' ? 'selected' : '' ?>>
                        Voucher Sách (Giảm theo %)
                    </option>
                    <option value="fixed" <?= ($editItem['type'] ?? '') == 'fixed' ? 'selected' : '' ?>>
                        Voucher Sách (Giảm tiền cố định)
                    </option>
                    <option value="free_shipping" <?= ($editItem['type'] ?? '') == 'free_shipping' ? 'selected' : '' ?>>
                        Voucher Vận Chuyển (Miễn phí)
                    </option>
                </select>
            </div>
        </div>

        <div class="row" id="value-row">
            <div class="col-md-6 mb-3">
                <label for="value" class="form-label fw-bold">Giá trị</label>
                <input type="number" step="0.01" class="form-control" id="value" name="value" value="<?= htmlspecialchars($editItem['value'] ?? '0') ?>" required>
                <small class="form-text text-muted">Nhập % (vd: 10), số tiền (vd: 50000), hoặc 0 cho voucher miễn phí vận chuyển.</small>
            </div>
            <div class="col-md-6 mb-3">
                <label for="min_order_value" class="form-label fw-bold">Áp dụng cho đơn hàng từ (₫)</label>
                <input type="number" step="1000" class="form-control" id="min_order_value" name="min_order_value" value="<?= htmlspecialchars($editItem['min_order_value'] ?? '0') ?>" required>
                <small class="form-text text-muted">Để 0 nếu không yêu cầu giá trị tối thiểu.</small>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label fw-bold">Ngày bắt đầu</label>
                <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="<?= !empty($editItem['start_date']) ? date('Y-m-d\TH:i', strtotime($editItem['start_date'])) : '' ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label fw-bold">Ngày kết thúc</label>
                <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="<?= !empty($editItem['end_date']) ? date('Y-m-d\TH:i', strtotime($editItem['end_date'])) : '' ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="usage_limit" class="form-label fw-bold">Giới hạn lượt sử dụng</label>
            <input type="number" class="form-control" id="usage_limit" name="usage_limit" value="<?= htmlspecialchars($editItem['usage_limit'] ?? '0') ?>" required>
            <small class="form-text text-muted">Nhập 0 nếu không giới hạn.</small>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="status" name="status" value="1" <?= (isset($editItem['status']) && $editItem['status'] == 1) || !$isEdit ? 'checked' : '' ?>>
            <label class="form-check-label" for="status">Kích hoạt</label>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?= APP_URL ?>/PromoCodeController/show" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> <?= $isEdit ? 'Lưu thay đổi' : 'Tạo mới' ?>
            </button>
        </div>
    </form>
</div>