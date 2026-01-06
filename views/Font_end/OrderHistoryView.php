<div class="container mt-5">
    <h2>Lịch sử đơn hàng của bạn</h2>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Mã hóa đơn</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>TT Thanh toán</th>
                <th>TT Giao hàng</th>
                <!-- Bỏ các cột không cần thiết để gọn hơn -->
                <th>Địa chỉ giao hàng</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['orders'])): foreach ($data['orders'] as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['order_code']) ?></td>
                <td><?= htmlspecialchars($order['created_at']) ?></td>
                <td><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</td>
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
                            case 'đang xử lý': $order_badge = 'bg-info text-dark'; break;
                            case 'đang giao': $order_badge = 'bg-primary'; break;
                            case 'hoàn thành': $order_badge = 'bg-success'; break;
                            case 'đã hủy': $order_badge = 'bg-danger'; break;
                            default: $order_badge = 'bg-warning text-dark'; break;
                        }
                    ?>
                    <span class="badge <?= $order_badge ?>"><?= htmlspecialchars($order_status) ?></span>
                </td>
                <td><?= htmlspecialchars($order['address']) ?></td>
                <td><a href="<?php echo APP_URL; ?>/Home/orderDetail/<?= $order['id'] ?>" class="btn btn-info btn-sm">Xem chi tiết</a></td> <!-- Đảm bảo đây là ID của đơn hàng -->
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="8" class="text-center">Bạn chưa có đơn hàng nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
