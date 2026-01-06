<div class="container mt-5">
    <h2>Chi tiết đơn hàng #<?= htmlspecialchars($data['orderId']) ?></h2>
    <?php if (!empty($data['order'])): $order = $data['order']; ?>
    <div class="mb-3">
        <p><b>Ngày đặt:</b> <?= htmlspecialchars($order['created_at']) ?></p>
        <p><b>Người nhận:</b> <?= htmlspecialchars($order['receiver']) ?></p>
        <p><b>Địa chỉ:</b> <?= htmlspecialchars($order['address']) ?></p>
        <p><b>Số điện thoại:</b> <?= htmlspecialchars($order['phone']) ?></p>
        <p><b>Tổng tiền:</b> <span class="text-danger fw-bold"><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</span></p>
        <p>
            <b>Trạng thái giao hàng:</b> 
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
        </p>
    </div>
    <?php endif; ?>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Mã sản phẩm</th>
                <th>Tên sản phẩm</th>
                <th>Hình ảnh</th>
                <th>Số lượng</th>
                <th>Giá bán</th>
                <th>Giá khuyến mại</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['details'])): foreach ($data['details'] as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_id']) ?></td>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td><img src="<?php echo APP_URL; ?>/public/images/<?= htmlspecialchars($item['image']) ?>" style="width:60px;height:60px;object-fit:contain;"></td>
                <td><?= htmlspecialchars($item['quantity']) ?></td>
                <td><?= number_format($item['price'], 0, ',', '.') ?> ₫</td>
                <td><?= number_format($item['sale_price'], 0, ',', '.') ?> ₫</td>
                <td><?= number_format($item['total'], 0, ',', '.') ?> ₫</td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="7" class="text-center">Không có dữ liệu chi tiết đơn hàng.</td></tr>
        <?php endif; ?>
        </tbody>
        <?php if (!empty($data['order'])): ?>
        <tfoot class="table-group-divider">
            <tr>
                <td colspan="6" class="text-end fw-bold">Tổng tiền thanh toán:</td>
                <td class="fw-bold text-danger"><?= number_format($data['order']['total_amount'], 0, ',', '.') ?> ₫</td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>
    <div class="d-flex justify-content-between align-items-center">
        <a href="<?php echo APP_URL; ?>/Home/orderHistory" class="btn btn-secondary">Quay lại lịch sử đơn hàng</a>
        <?php if (!empty($data['order']) && $data['order']['order_status'] == 'Đang giao'): ?>
            <a href="<?= APP_URL ?>/Home/confirmDelivery/<?= $data['order']['id'] ?>" class="btn btn-success" onclick="return confirm('Bạn có chắc chắn muốn xác nhận đã nhận được hàng?');">
                <i class="bi bi-check-circle-fill"></i> Đã nhận được hàng
            </a>
        <?php endif; ?>
    </div>
</div>
