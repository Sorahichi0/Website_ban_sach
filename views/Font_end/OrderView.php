<?php if (!empty($data['success'])): ?>
    <div class="alert alert-success text-center mt-3">
        <?= htmlspecialchars($data['success']) ?>
    </div>
<?php endif; ?>

<?php 
    // Gom dữ liệu giỏ hàng về 1 biến duy nhất
    $listProductOrder = $data["listProductOrder"] ?? $data["cart"] ?? [];
    $totalAmount = 0;
    $productDiscountAmount = 0; // Giảm giá sản phẩm
    $shippingDiscountAmount = 0; // Giảm giá vận chuyển
    $finalAmount = 0;
    $productPromo = $_SESSION['product_promo_code'] ?? null; // Voucher giảm giá sản phẩm
    $shippingPromo = $_SESSION['shipping_promo_code'] ?? null; // Voucher miễn phí vận chuyển
    
    $bothPromosApplied = ($productPromo !== null && $shippingPromo !== null); // Kiểm tra cả hai loại voucher đã được áp dụng

    $promoSuccess = $_SESSION['promo_success'] ?? null;
    $promoError = $_SESSION['promo_error'] ?? null;
    unset($_SESSION['promo_success']); // Xóa message sau khi hiển thị
    unset($_SESSION['promo_error']); // Xóa message sau khi hiển thị
?>

<div class="container my-5">
    <h2 class="mb-4">🛒 Giỏ Hàng Của Bạn</h2>

    <form action="<?= APP_URL ?>/Home/update" method="post" id="cart-update-form">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>STT</th>
                <th>Sản phẩm</th>
                <th>Giá bán</th>
                <th>Khuyến mãi</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($listProductOrder)): ?>
            <?php 
                $i = 0;
                foreach ($listProductOrder as $k => $v): 
                    $i++;
                    $gia = $v['giaxuat'] ?? $v['price'];
                    $km  = $v['khuyenmai'] ?? 0;
                    $qty = $v['qty'] ?? $v['quantity'] ?? 1;
                    $thanhtien = ($gia - $gia * $km / 100) * $qty;
                    $totalAmount += $thanhtien;
            ?>
            <tr>
                <td><?= $i ?></td>
                <td>
                    <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($v['hinhanh'] ?? $v['image'] ?? '') ?>" 
                         class="card-img-top" style="width: 100%; height: 9rem; object-fit: contain;">
                    <br>
                    <?= htmlspecialchars($v["masp"] ?? $v["id"]) ?><br>
                    <?= htmlspecialchars($v["tensp"] ?? $v["name"]) ?>
                </td>
                <td><?= number_format($gia, 0, ',', '.') ?> ₫</td>
                <td><?= htmlspecialchars($km) ?>%</td>
                <td>
                    <input type="number" name="qty[<?= $k ?>]" 
                           value="<?= htmlspecialchars($qty) ?>" 
                           min="1" class="form-control form-control-sm" style="width: 80px;">
                </td>
                <td><?= number_format($thanhtien, 0, ',', '.') ?> ₫</td>
                <td>
                    <a href="<?= APP_URL ?>/Home/delete/<?= htmlspecialchars($v['masp'] ?? $v['id']) ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?');">
                        🗑️ Xoá
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center text-muted">🛒 Giỏ hàng của bạn đang trống</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    </form> <!-- Đóng form update ở đây -->

    <?php if (!empty($listProductOrder)): ?>
    <div class="row justify-content-end">
        <div class="col-md-6">
            <h4 class="mb-3">Tổng cộng</h4>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Tổng tiền hàng</span>
                    <strong><?= number_format($totalAmount, 0, ',', '.') ?> ₫</strong>
                </li>
                <?php
                // Tính toán giảm giá sản phẩm
                if ($productPromo) {
                    if ($productPromo['type'] == 'percentage') {
                        $productDiscountAmount = ($totalAmount * $productPromo['value']) / 100;
                    } else { // fixed
                        $productDiscountAmount = $productPromo['value'];
                    }
                    if ($productDiscountAmount > $totalAmount) $productDiscountAmount = $totalAmount;
                }

                // Tính toán giảm giá vận chuyển (chỉ hiển thị ở đây, không ảnh hưởng totalAmount trực tiếp)
                // Phí vận chuyển thực tế sẽ được tính ở trang checkoutInfo
                // Ở đây chỉ hiển thị nếu có freeship voucher
                if ($shippingPromo && $shippingPromo['type'] === 'free_shipping') {
                    // Giả định phí vận chuyển trung bình để hiển thị
                    $shippingDiscountAmount = 15000; // Có thể lấy từ cấu hình hoặc tính toán ước lượng
                }

                $finalAmount = $totalAmount - $productDiscountAmount;
                if ($finalAmount < 0) $finalAmount = 0;

                // Lưu tổng tiền cuối cùng vào session (có thể cần điều chỉnh nếu có phí vận chuyển)
                // $_SESSION['final_total'] = $finalAmount; 
                ?>
                <?php if ($productPromo): ?>
                <li class="list-group-item d-flex justify-content-between bg-light align-items-center">
                    <span class="text-success">Voucher giảm giá (<strong><?= htmlspecialchars($productPromo['code']) ?></strong>)</span>
                    <span class="text-success">−<?= number_format($productDiscountAmount, 0, ',', '.') ?> ₫ <a href="<?= APP_URL ?>/Home/removePromoCode?type=product" class="btn-close ms-2"></a></span>
                </li>
                <?php endif; ?>
                <?php if ($shippingPromo): ?>
                <li class="list-group-item d-flex justify-content-between bg-light text-muted">
                    <h6 class="my-0">Mã miễn phí vận chuyển (<?= htmlspecialchars($shippingPromo['code']) ?>)</h6>
                    <small>Sẽ được áp dụng ở trang thanh toán</small>
                </li>
                <?php endif; ?>
                <?php if ($shippingPromo): ?>
                <li class="list-group-item d-flex justify-content-between bg-light text-success">
                    <h6 class="my-0">Mã miễn phí vận chuyển (<?= htmlspecialchars($shippingPromo['code']) ?>)</h6>
                    <small>Sẽ được áp dụng ở trang thanh toán</small>
                </li>
                <?php endif; ?>

                <li class="list-group-item d-flex justify-content-between" id="total-row">
                    <strong>Tổng thanh toán</strong>
                    <strong id="final-total-amount"><?= number_format($finalAmount, 0, ',', '.') ?> ₫</strong>
                </li>
            </ul>
            
            <?php if (!$productPromo): // Chỉ hiển thị form nếu chưa có voucher sản phẩm nào được áp dụng ?>
                <!-- Danh sách mã khuyến mại có sẵn -->
                <?php if (!empty($data['available_codes'])): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Chọn mã giảm giá có sẵn:</label>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($data['available_codes'] as $promo): ?>
                            <?php if ($promo['type'] == 'free_shipping') continue; // Bỏ qua voucher freeship ?>
                            <button type="button" class="btn btn-outline-success btn-sm available-promo-code" 
                                    data-code="<?= htmlspecialchars($promo['code']) ?>" data-type="<?= htmlspecialchars($promo['type']) ?>"
                                    data-min-value="<?= htmlspecialchars($promo['min_order_value']) ?>">
                                <?= htmlspecialchars($promo['code']) ?>
                                <small class="d-block">(Giảm <?= $promo['type'] == 'percentage' ? $promo['value'] . '%' : number_format($promo['value']) . ' ₫' ?> cho sản phẩm)</small>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Form áp dụng mã khuyến mại -->
                <form action="<?= APP_URL ?>/Home/applyPromoCode" method="POST" id="promo-form" class="card p-2 mb-3">
                    <div class="input-group">
                        <input type="hidden" name="current_order_value" value="<?= $totalAmount ?>">
                        <input type="text" id="promo-code-input" class="form-control" name="promo_code" placeholder="Mã giảm giá sản phẩm">
                        <button type="submit" id="apply-promo-btn" class="btn btn-secondary">Áp dụng</button>
                    </div>
                    <div id="promo-message" class="mt-2">
                        <?php if ($promoSuccess): ?><div class="text-success"><?= $promoSuccess ?></div><?php endif; ?>
                        <?php if ($promoError): ?><div class="text-danger"><?= $promoError ?></div><?php endif; ?>
                    </div>
                </form>
            <?php endif; ?>

            <div class="d-flex justify-content-end">
                <button type="submit" form="cart-update-form" class="btn btn-primary">🔄 Cập nhật giỏ hàng</button>
            <a href="<?= APP_URL . '/Home/checkoutInfo' ?>" class="btn btn-success ms-2">🛒 Đặt hàng</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Script xử lý AJAX cho mã khuyến mại -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const promoForm = document.getElementById('promo-form');
    const promoMessage = document.getElementById('promo-message');
    const subTotal = <?= $totalAmount ?>; // Tổng tiền hàng

    // Xử lý khi người dùng nhấn vào một voucher có sẵn
    const availableCodes = document.querySelectorAll('.available-promo-code');
    availableCodes.forEach(button => {
        button.addEventListener('click', function() {
            const code = this.dataset.code;
            const minValue = parseFloat(this.dataset.minValue);
            
            if (subTotal < minValue) {
                promoMessage.innerHTML = `<div class="text-danger">Voucher này chỉ áp dụng cho đơn hàng từ ${new Intl.NumberFormat('vi-VN').format(minValue)} ₫.</div>`;
                return;
            }
            // Điền mã và submit form
            promoForm.querySelector('input[name="promo_code"]').value = code;
            promoForm.submit();
        });
    });
});
</script>
