<?php
$order = $data['order'];
$details = $data['details'];
$statuses = ['Chờ xác nhận', 'Đang xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'];
?>
<div class="container mt-5" id="invoice">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Chi tiết Đơn hàng #<?= htmlspecialchars($order['order_code']) ?></h2>
        <div>
            <button onclick="window.print()" class="btn btn-secondary"><i class="bi bi-printer"></i> In hóa đơn</button>
            <a href="<?= APP_URL ?>/AdminOrderController/show" class="btn btn-outline-secondary">Quay lại</a>
        </div>
    </div>

    <div class="row">
        <!-- Cột thông tin khách hàng và đơn hàng -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header">Thông tin Khách hàng</div>
                <div class="card-body">
                    <p><strong>Tên người nhận:</strong> <?= htmlspecialchars($order['receiver']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($order['user_email']) ?></p>
                    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                    <p><strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($order['address']) ?></p>
                </div>
            </div>
        </div>

        <!-- Cột trạng thái và cập nhật -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header">Thông tin Đơn hàng</div>
                <div class="card-body">
                    <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                    <p><strong>Trạng thái thanh toán:</strong>
                        <?php
                            $payment_status = $order['transaction_info'] ?? 'Chờ thanh toán';
                            $payment_badge = 'bg-warning text-dark';
                            if (strtolower($payment_status) == 'dathanhtoan') {
                                $payment_badge = 'bg-success';
                                $payment_status = 'Đã thanh toán';
                            }
                        ?>
                        <span class="badge <?= $payment_badge ?>"><?= $payment_status ?></span>
                    </p>
                    <hr>
                    <form action="<?= APP_URL ?>/AdminOrderController/updateStatus/<?= $order['id'] ?>" method="POST">
                        <div class="mb-3">
                            <label for="order_status" class="form-label fw-bold">Cập nhật trạng thái giao hàng:</label>
                            <select name="order_status" id="order_status" class="form-select">
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?= $status ?>" <?= ($order['order_status'] == $status) ? 'selected' : '' ?>>
                                        <?= $status ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng chi tiết sản phẩm -->
    <div class="card shadow-sm">
        <div class="card-header">Chi tiết Sản phẩm</div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-end">Đơn giá</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($details)): foreach ($details as $item): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($item['product_name']) ?><br>
                            <small class="text-muted">Mã: <?= htmlspecialchars($item['product_id']) ?></small>
                        </td>
                        <td class="text-center"><?= htmlspecialchars($item['quantity']) ?></td>
                        <td class="text-end"><?= number_format($item['sale_price']) ?> ₫</td>
                        <td class="text-end"><?= number_format($item['total']) ?> ₫</td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="3" class="text-end">Tổng cộng:</td>
                        <td class="text-end text-danger fs-5"><?= number_format($order['total_amount']) ?> ₫</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #invoice, #invoice * {
            visibility: visible;
        }
        #invoice {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .btn, .form-select, form button {
            display: none !important;
        }
    }
</style>