<?php
$order = $data['order'] ?? null;
$paymentMessage = $_SESSION['payment_success_message'] ?? null;
unset($_SESSION['payment_success_message']); // Xóa session sau khi lấy
?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <?php if ($paymentMessage): ?>
                        <h1 class="text-success"><i class="bi bi-credit-card-fill"></i></h1>
                        <h2 class="card-title"><?= htmlspecialchars($paymentMessage) ?></h2>
                        <p class="card-text">Cảm ơn bạn đã tin tưởng và mua hàng.</p>
                    <?php else: ?>
                        <h1 class="text-success"><i class="bi bi-check-circle-fill"></i></h1>
                        <h2 class="card-title">Đặt hàng thành công!</h2>
                        <p class="card-text">Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được ghi nhận.</p>
                    <?php endif; ?>
                    <?php if ($order): ?>
                        <p>Mã đơn hàng của bạn là: <strong>#<?= htmlspecialchars($order['order_code']) ?></strong></p>
                    <?php endif; ?>
                    <a href="<?= APP_URL ?>/Home/orderHistory" class="btn btn-primary">Xem lịch sử đơn hàng</a>
                    <a href="<?= APP_URL ?>/Home" class="btn btn-outline-secondary">Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    </div>
</div>