<div class="container mt-5">
    <h2 class="text-primary mb-4">Quản lý Đơn hàng</h2>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Mã ĐH</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>TT Thanh toán</th>
                <th>TT Giao hàng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['orders'])): foreach ($data['orders'] as $order): ?>
            <tr>
                <td><strong><?= htmlspecialchars($order['order_code']) ?></strong></td>
                <td>
                    <?= htmlspecialchars($order['receiver']) ?><br>
                    <small class="text-muted"><?= htmlspecialchars($order['user_email']) ?></small>
                </td>
                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                <td class="text-danger fw-bold"><?= number_format($order['total_amount']) ?> ₫</td>
                <td>
                    <?php
                        $payment_status = $order['transaction_info'] ?? 'Chờ thanh toán';
                        $payment_badge = 'bg-warning text-dark';
                        if (strtolower($payment_status) == 'dathanhtoan') {
                            $payment_badge = 'bg-success';
                            $payment_status = 'Đã thanh toán';
                        }
                    ?>
                    <span class="badge <?= $payment_badge ?>"><?= $payment_status ?></span>
                </td>
                <td>
                     <?php
                        $order_status = $order['order_status'] ?? 'Chờ xác nhận';
                        $order_badge = 'bg-secondary';
                        switch (strtolower($order_status)) {
                            case 'đang xử lý': $order_badge = 'bg-info'; break;
                            case 'đang giao': $order_badge = 'bg-primary'; break;
                            case 'hoàn thành': $order_badge = 'bg-success'; break;
                            case 'đã hủy': $order_badge = 'bg-danger'; break;
                        }
                    ?>
                    <span class="badge <?= $order_badge ?>"><?= htmlspecialchars($order_status) ?></span>
                </td>
                <td>
                    <a href="<?= APP_URL ?>/AdminOrderController/detail/<?= $order['id'] ?>" class="btn btn-info btn-sm">
                        Xem & Xử lý
                    </a>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="7" class="text-center">Chưa có đơn hàng nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>