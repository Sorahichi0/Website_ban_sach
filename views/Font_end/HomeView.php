<div class="container py-5">
    <div class="row">
        <?php if (isset($data['categoryName']) && !empty($data['categoryName'])): ?>
            <h2 class="mb-4">Sản phẩm thuộc danh mục: <span class="text-primary"><?= htmlspecialchars($data['categoryName']) ?></span></h2>
        <?php else: ?>
            <h2 class="mb-4">Sản phẩm nổi bật</h2>
        <?php endif; ?>

        <?php if (!empty($data['productList'])): ?>
            <?php foreach ($data['productList'] as $product): ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($product['hinhanh']) ?>" class="card-img-top p-3" style="height: 300px; object-fit: contain;" alt="<?= htmlspecialchars($product['tensp']) ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($product['tensp']) ?></h5>
                            <?php if ($product['khuyenmai'] > 0): ?>
                                <p class="card-text">
                                    <span class="text-danger fw-bold"><?= number_format($product['discounted_price']) ?> ₫</span>
                                    <span class="text-muted text-decoration-line-through"><?= number_format($product['giaXuat']) ?> ₫</span>
                                </p>
                            <?php else: ?>
                                <p class="card-text text-danger fw-bold"><?= number_format($product['giaXuat']) ?> ₫</p>
                            <?php endif; ?>
                            <p class="card-text text-muted">Đã bán: <?= htmlspecialchars($product['sold_count']) ?></p>
                            <div class="mt-auto">
                                <a href="<?= APP_URL ?>/Home/detail/<?= htmlspecialchars($product['masp']) ?>" class="btn btn-primary w-100">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center text-muted">Không có sản phẩm nào để hiển thị.</p>
            </div>
        <?php endif; ?>
    </div>
</div>