<?php
$product = $data['product'] ?? null;
if (!$product) {
    echo '<div class="container my-5"><div class="alert alert-danger">Sản phẩm không tồn tại.</div></div>';
    return;
}
?>

<div class="container my-5">
    <div class="row">
        <!-- Cột hình ảnh -->
        <div class="col-md-5">
            <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($product['hinhanh']) ?>" class="img-fluid rounded shadow-sm" alt="<?= htmlspecialchars($product['tensp']) ?>">
        </div>

        <!-- Cột thông tin sản phẩm -->
        <div class="col-md-7">
            <h2 class="mb-3"><?= htmlspecialchars($product['tensp']) ?></h2>
            
            <p class="mb-2"><strong>Tác giả:</strong> <?= htmlspecialchars($product['author']) ?></p>
            <p class="mb-3"><strong>Nhà xuất bản:</strong> <?= htmlspecialchars($product['publisher']) ?></p>

            <div class="bg-light p-3 rounded mb-3">
                <?php if ($product['khuyenmai'] > 0): ?>
                    <h4 class="text-danger fw-bold"><?= number_format($product['discounted_price']) ?> ₫</h4>
                    <span class="text-muted text-decoration-line-through"><?= number_format($product['giaXuat']) ?> ₫</span>
                    <span class="badge bg-danger ms-2">-<?= $product['khuyenmai'] ?>%</span>
                <?php else: ?>
                    <h4 class="text-danger fw-bold"><?= number_format($product['giaXuat']) ?> ₫</h4>
                <?php endif; ?>
            </div>

            <p class="mb-2"><strong>Đã bán:</strong> <?= htmlspecialchars($product['sold_count']) ?></p>
            <p class="mb-4"><strong>Số lượng còn lại:</strong> <?= htmlspecialchars($product['soluong']) ?></p>

            <form action="<?= APP_URL ?>/Home/addtocard/<?= htmlspecialchars($product['masp']) ?>" method="POST">
                <div class="mb-3 d-flex align-items-center">
                    <label for="quantity" class="form-label me-2 mb-0">Số lượng mua:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="<?= htmlspecialchars($product['soluong']) ?>" style="width: 100px;" <?= $product['soluong'] <= 0 ? 'disabled' : '' ?>>
                </div>

                <div class="d-grid gap-2">
                    <?php if ($product['soluong'] > 0): ?>
                        <button type="submit" class="btn btn-success btn-lg">
                            🛒 Thêm vào giỏ hàng
                        </button>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-lg" disabled>Hết hàng</button>
                    <?php endif; ?>
                </div>
            </form>

            <hr class="my-4">
            <h5 class="mb-3">Mô tả sản phẩm</h5>
            <p><?= nl2br(htmlspecialchars($product['mota'])) ?></p>
        </div>
    </div>

    <!-- Phần đánh giá và bình luận -->
    <div class="row mt-5">
        <div class="col-12">
            <hr>
            <h3 class="mb-4">Đánh giá & Bình luận</h3>

            <!-- Form gửi bình luận -->
            <?php if (isset($_SESSION['user'])): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Gửi đánh giá của bạn</h5>
                        <form action="<?= APP_URL ?>/Home/addComment/<?= htmlspecialchars($product['masp']) ?>" method="POST">
                            <div class="mb-3">
                                <label for="rating" class="form-label">Đánh giá:</label>
                                <select name="rating" id="rating" class="form-select" style="width: 150px;">
                                    <option value="5">⭐⭐⭐⭐⭐ (Tuyệt vời)</option>
                                    <option value="4">⭐⭐⭐⭐ (Tốt)</option>
                                    <option value="3">⭐⭐⭐ (Khá)</option>
                                    <option value="2">⭐⭐ (Tệ)</option>
                                    <option value="1">⭐ (Rất tệ)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Nội dung bình luận:</label>
                                <textarea name="content" id="content" rows="3" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi bình luận</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    Vui lòng <a href="<?= APP_URL ?>/AuthController/ShowLogin">đăng nhập</a> để gửi bình luận.
                </div>
            <?php endif; ?>

            <!-- Danh sách bình luận -->
            <h5 class="mb-3">Các bình luận đã có</h5>
            <?php if (!empty($data['comments'])): ?>
                <?php foreach ($data['comments'] as $comment): ?>
                    <div class="d-flex mb-3 border-bottom pb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                <?= strtoupper(substr($comment['user_name'], 0, 1)) ?>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mt-0 mb-1"><?= htmlspecialchars($comment['user_name']) ?></h6>
                            <div class="text-warning mb-1">
                                <?php for ($i = 0; $i < $comment['rating']; $i++) echo '⭐'; ?>
                            </div>
                            <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                            <small class="text-muted">Đăng vào: <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Chưa có bình luận nào cho sản phẩm này.</p>
            <?php endif; ?>
        </div>
    </div>
</div>