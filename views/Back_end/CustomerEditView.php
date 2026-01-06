<?php $customer = $data['customer']; ?>
<div class="container mt-5">
        <div class="row mb-3">
            <div class="col-12">
                <h3 class="text-primary"><i class="bi bi-pencil-square"></i> Chỉnh sửa thông tin khách hàng</h3>
            </div>
        </div>

        <?php if (isset($_GET['message']) && $_GET['message'] == 'update_error'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Cập nhật khách hàng thất bại! Vui lòng thử lại.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="<?= APP_URL ?>/CustomerController/update/<?= htmlspecialchars($customer['user_id']) ?>" method="POST">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" value="<?= htmlspecialchars($customer['fullname'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($customer['address'] ?? '') ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            <a href="<?= APP_URL ?>/CustomerController/show" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>
