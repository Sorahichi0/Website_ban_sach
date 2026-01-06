<?php
$shippingMethods = $data['shipping_methods'] ?? [];
$cart = $data['listProductOrder'] ?? []; // Giả sử giỏ hàng được truyền vào
$productPromo = $data['product_promo_code'] ?? null;
$shippingPromo = $data['shipping_promo_code'] ?? null;

// Tính tổng tiền hàng
$subTotal = 0;
foreach ($cart as $item) {
    $price = $item['giaxuat'] * (1 - $item['khuyenmai'] / 100);
    $subTotal += $price * $item['qty'];
}

// Tính giảm giá sản phẩm ban đầu (chỉ từ productPromo)
$productDiscountAmount = 0;
if ($productPromo) {
    if ($productPromo['type'] == 'percentage') {
        $productDiscountAmount = ($subTotal * $productPromo['value']) / 100;
    } else { // fixed
        $productDiscountAmount = $productPromo['value'];
    }
}
if ($productDiscountAmount > $subTotal) $productDiscountAmount = $subTotal;

// Phí vận chuyển ban đầu (sẽ được cập nhật bởi JS)
$initialShippingCost = !empty($shippingMethods) ? $shippingMethods[0]['cost'] : 0;
if ($shippingPromo && $shippingPromo['type'] === 'free_shipping') {
    $initialShippingCost = 0;
}

?>
<div class="container my-5">
    <h2 class="text-center mb-4">Thông tin thanh toán</h2>
    <form action="<?= APP_URL ?>/Home/checkoutSave" method="POST">
        <div class="row">
            <!-- Cột thông tin giao hàng -->
            <div class="col-md-7">
                <h4>Thông tin giao hàng</h4>
                <hr>
                <?php if (isset($data['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="receiver" class="form-label">Họ tên người nhận</label>
                    <input type="text" class="form-control" id="receiver" name="receiver" value="<?= htmlspecialchars($_SESSION['user']['fullname'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($_SESSION['user']['phone'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ nhận hàng</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($_SESSION['user']['address'] ?? '') ?>" required>
                </div>

                <h4 class="mt-4">Hình thức giao hàng</h4>
                <hr>
                <?php if (!empty($shippingMethods)): ?>
                    <?php foreach ($shippingMethods as $index => $method): ?>
                        <div class="form-check border p-3 mb-2 rounded">
                            <input class="form-check-input" type="radio" name="shipping_method_id" id="shipping-<?= $method['id'] ?>" value="<?= $method['id'] ?>" <?= $index == 0 ? 'checked' : '' ?> data-cost="<?= $method['cost'] ?>">
                            <label class="form-check-label w-100" for="shipping-<?= $method['id'] ?>">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong><?= htmlspecialchars($method['name']) ?></strong>
                                        <p class="mb-0 text-muted small"><?= htmlspecialchars($method['description']) ?></p>
                                    </div>
                                    <span class="fw-bold"><?= number_format($method['cost']) ?> ₫</span>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Không có hình thức giao hàng nào.</p>
                <?php endif; ?>

                <!-- Mục Voucher Vận Chuyển -->
                <h4 class="mt-4">Voucher Vận Chuyển</h4>
                <hr>
                <?php if ($shippingPromo): ?>
                    <div class="alert alert-success d-flex justify-content-between align-items-center">
                        <span>Đã áp dụng mã: <strong><?= htmlspecialchars($shippingPromo['code']) ?></strong></span>
                        <a href="<?= APP_URL ?>/Home/removePromoCode?type=shipping" class="btn-close"></a>
                    </div>
                <?php else: ?>
                    <!-- Form áp dụng voucher -->
                    <form action="<?= APP_URL ?>/Home/applyPromoCode" method="POST" id="shipping-promo-form">
                        <input type="hidden" name="current_order_value" value="<?= $subTotal ?>"> <!-- Gửi subTotal để kiểm tra min_order_value -->
                        <input type="hidden" name="redirect_to" value="checkout">
                        <div class="input-group">
                            <input type="text" class="form-control" name="promo_code" placeholder="Nhập mã miễn phí vận chuyển...">
                            <button class="btn btn-outline-secondary" type="submit">Áp dụng</button>
                        </div>
                    </form>
                    <!-- Hiển thị lỗi nếu có -->
                    <?php if(isset($_SESSION['promo_error'])): ?>
                        <small class="text-danger d-block mt-1"><?= $_SESSION['promo_error'] ?></small>
                        <?php unset($_SESSION['promo_error']); ?>
                    <?php endif; ?>

                    <!-- Hiển thị các voucher có sẵn -->
                    <?php $availableShippingCodes = array_filter($data['available_codes'] ?? [], fn($c) => $c['type'] === 'free_shipping'); ?>
                    <?php if(!empty($availableShippingCodes)): ?>
                        <div class="mt-2">
                            <small class="fw-bold">Mã có sẵn:</small>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                <?php foreach($availableShippingCodes as $promo): ?>
                                    <button type="button" class="btn btn-outline-success btn-sm available-shipping-promo" data-code="<?= htmlspecialchars($promo['code']) ?>"><?= htmlspecialchars($promo['code']) ?></button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- Kết thúc mục Voucher Vận Chuyển -->




                <h4 class="mt-4">Phương thức thanh toán</h4>
                <hr>
                <div class="form-check border p-3 mb-2 rounded">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" checked>
                    <label class="form-check-label" for="payment_cod">
                        <strong>Thanh toán khi nhận hàng (COD)</strong>
                    </label>
                </div>
                <div class="form-check border p-3 mb-2 rounded">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment_vnpay" value="vnpay">
                    <label class="form-check-label" for="payment_vnpay">
                        <strong>Thanh toán qua VNPAY</strong>
                    </label>
                </div>
            </div>

            <!-- Cột tóm tắt đơn hàng -->
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Tóm tắt đơn hàng</h4>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Tạm tính:</span>
                            <span id="subtotal-amount"><?= number_format($subTotal) ?> ₫</span>
                            <input type="hidden" id="hidden-subtotal" value="<?= $subTotal ?>">
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Phí vận chuyển:</span>
                            <span id="shipping-cost-display"><?= number_format($initialShippingCost) ?> ₫</span>
                        </div>
                        <?php if ($productDiscountAmount > 0): ?>
                        <div class="d-flex justify-content-between text-success">
                            <span>Giảm giá sản phẩm:</span>
                            <span id="product-discount-display">- <?= number_format($productDiscountAmount) ?> ₫</span>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between text-success" id="shipping-discount-row" style="display: none;">
                            <span>Voucher Freeship:</span>
                            <span id="shipping-discount-display"></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fs-5 fw-bold">
                            <span>Tổng cộng:</span>
                            <span id="total-amount-display" class="text-danger">0 ₫</span>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Hoàn tất đặt hàng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subTotal = parseFloat(document.getElementById('hidden-subtotal').value);
    const productPromo = <?= json_encode($productPromo) ?>;
    const shippingPromo = <?= json_encode($shippingPromo) ?>;
    const shippingRadios = document.querySelectorAll('input[name="shipping_method_id"]');
    const shippingCostDisplay = document.getElementById('shipping-cost-display');
    const productDiscountDisplay = document.getElementById('product-discount-display');
    const shippingDiscountDisplay = document.getElementById('shipping-discount-display');
    const shippingDiscountRow = document.getElementById('shipping-discount-row');
    const totalAmountDisplay = document.getElementById('total-amount-display');

    function updateTotal() {
        const checkedRadio = document.querySelector('input[name="shipping_method_id"]:checked');
        if (!checkedRadio) return;

        let currentShippingCost = parseFloat(checkedRadio.dataset.cost);
        let productDiscount = 0;

        if (productPromo) {
            productDiscount = productPromo.type === 'percentage' ? (subTotal * productPromo.value) / 100 : productPromo.value;
            if (productDiscount > subTotal) productDiscount = subTotal;
        }

        // Cập nhật hiển thị phí vận chuyển gốc
        shippingCostDisplay.textContent = new Intl.NumberFormat('vi-VN').format(currentShippingCost) + ' ₫';

        // Nếu có voucher freeship, cập nhật lại phí vận chuyển và hiển thị mục giảm giá
        if (shippingPromo && shippingPromo.type === 'free_shipping') {
            if (shippingDiscountDisplay) {
                shippingDiscountDisplay.textContent = '- ' + new Intl.NumberFormat('vi-VN').format(currentShippingCost) + ' ₫';
            }
            if (shippingDiscountRow) shippingDiscountRow.style.display = 'flex';
            currentShippingCost = 0; // Phí vận chuyển thực tế là 0
        } else {
            if (shippingDiscountRow) shippingDiscountRow.style.display = 'none';
        }
        const finalTotal = subTotal - productDiscount + currentShippingCost;

        totalAmountDisplay.textContent = new Intl.NumberFormat('vi-VN').format(finalTotal) + ' ₫';
    }

    shippingRadios.forEach(radio => {
        radio.addEventListener('change', updateTotal);
    });

    // Xử lý khi nhấn vào nút voucher có sẵn
    const availablePromoButtons = document.querySelectorAll('.available-shipping-promo');
    availablePromoButtons.forEach(button => {
        button.addEventListener('click', function() {
            const code = this.dataset.code;
            const form = document.getElementById('shipping-promo-form');
            form.querySelector('input[name="promo_code"]').value = code;
            form.submit();
        });
    });

    // Nếu có voucher freeship được áp dụng, hiển thị dòng giảm giá ngay khi tải trang
    if (shippingPromo && shippingPromo.type === 'free_shipping') {
        if (shippingDiscountRow) shippingDiscountRow.style.display = 'flex';
    }

    // Initial calculation
    updateTotal(); // Gọi lần đầu để tính toán và hiển thị đúng
});
</script>