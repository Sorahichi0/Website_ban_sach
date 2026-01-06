

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-search"></i> Tìm kiếm nâng cao
                    </h3>
                </div>
                <div class="card-body">
                    <form action="<?= APP_URL ?>/index.php" method="GET">
    <input type="hidden" name="url" value="Home/advancedSearch">
                        <div class="row">
                            <!-- Tên sách -->
                            <div class="col-md-6 mb-3">
                                <label for="tensp" class="form-label">Tên sách</label>
                                <input type="text" class="form-control" id="tensp" name="tensp" placeholder="Nhập tên sách..." value="<?= htmlspecialchars($data['old_inputs']['tensp'] ?? '') ?>">
                            </div>

                            <!-- Tác giả -->
                            <div class="col-md-6 mb-3">
                                <label for="author" class="form-label">Tác giả</label>
                                <input type="text" class="form-control" id="author" name="author" placeholder="Nhập tên tác giả..." value="<?= htmlspecialchars($data['old_inputs']['author'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Thể loại -->
                            <div class="col-md-4 mb-3">
                                <label for="maLoaiSP" class="form-label">Thể loại</label>
                                <select class="form-select" id="maLoaiSP" name="maLoaiSP">
                                    <option value="">Tất cả thể loại</option>
                                    <?php foreach ($data['categories'] as $category) : ?>
                                        <option value="<?= $category['maLoaiSP'] ?>" <?= (($data['old_inputs']['maLoaiSP'] ?? '') == $category['maLoaiSP']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['tenLoaiSP']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Nhà xuất bản -->
                            <div class="col-md-4 mb-3">
                                <label for="publisher" class="form-label">Nhà xuất bản</label>
                                <select class="form-select" id="publisher" name="publisher">
                                    <option value="">Tất cả NXB</option>
                                    <?php foreach ($data['publishers'] as $publisher) : ?>
                                        <option value="<?= htmlspecialchars($publisher['publisher']) ?>" <?= (($data['old_inputs']['publisher'] ?? '') == $publisher['publisher']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($publisher['publisher']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Đánh giá -->
                            <div class="col-md-4 mb-3">
                                <label for="rating" class="form-label">Đánh giá từ</label>
                                <select class="form-select" id="rating" name="rating">
                                    <option value="">Tất cả</option>
                                    <?php for ($i = 5; $i >= 1; $i--) : ?>
                                        <option value="<?= $i ?>" <?= (($data['old_inputs']['rating'] ?? '') == $i) ? 'selected' : '' ?>>
                                            Từ <?= $i ?> sao
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Khoảng giá -->
                            <div class="col-md-6 mb-3">
                                <label for="min_price" class="form-label">Giá từ</label>
                                <input type="number" class="form-control" id="min_price" name="min_price" placeholder="Giá thấp nhất" min="0" value="<?= htmlspecialchars($data['old_inputs']['min_price'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="max_price" class="form-label">Đến</label>
                                <input type="number" class="form-control" id="max_price" name="max_price" placeholder="Giá cao nhất" min="0" value="<?= htmlspecialchars($data['old_inputs']['max_price'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="<?php echo APP_URL; ?>/Home/advancedSearch" class="btn btn-secondary me-2">Xóa bộ lọc</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Hiển thị kết quả tìm kiếm -->
    <?php if ($data['searchPerformed']) : ?>
    <div class="row mt-5">
        <div class="col-lg-12">
            <h3>Kết quả tìm kiếm</h3>
            <hr>
            <div class="row gy-4">
                <?php if (empty($data['products'])) : ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <p>Không tìm thấy sản phẩm nào phù hợp với tiêu chí của bạn.</p>
                            <p>Vui lòng thử lại với các bộ lọc khác.</p>
                        </div>
                    </div>
                <?php else : ?>
                    <?php foreach ($data['products'] as $product) : ?>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($product['hinhanh']) ?>" class="card-img-top p-3" style="height: 300px; object-fit: contain;" alt="<?= htmlspecialchars($product['tensp']) ?>">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($product['tensp']) ?></h5>
                                    
                                    <!-- Giá sản phẩm -->
                                    <?php if ($product['khuyenmai'] > 0): ?>
                                        <p class="card-text">
                                            <span class="text-danger fw-bold"><?= number_format($product['discounted_price']) ?> ₫</span>
                                            <span class="text-muted text-decoration-line-through"><?= number_format($product['giaXuat']) ?> ₫</span>
                                        </p>
                                    <?php else: ?>
                                        <p class="card-text text-danger fw-bold"><?= number_format($product['giaXuat']) ?> ₫</p>
                                    <?php endif; ?>

                                    <!-- Đã bán -->
                                    <p class="card-text text-muted">Đã bán: <?= htmlspecialchars($product['sold_count'] ?? 0) ?></p>
                                    
                                    <!-- Font Awesome -->
                                    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

                                    <!-- Hiển thị rating -->
                                    <?php if ($product['avg_rating'] !== null): ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <?php 
                                                $rating = round($product['avg_rating']); 
                                                for($i = 1; $i <= 5; $i++): 
                                            ?>
                                                <i class="fa-star <?= $i <= $rating ? 'fas text-warning' : 'far' ?>"></i>
                                            <?php endfor; ?>
                                            <span class="ms-1 text-muted">(<?= round($product['avg_rating'], 1) ?>)</span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="mt-auto">
                                        <a href="<?= APP_URL ?>/Home/detail/<?= htmlspecialchars($product['masp']) ?>" class="btn btn-primary w-100">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>


<style>
    .product-card {
        transition: transform .2s, box-shadow .2s;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .card-title a {
        display: -webkit-box;
        line-clamp: 2;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 48px; /* Giữ chiều cao cho 2 dòng */
    }
</style>