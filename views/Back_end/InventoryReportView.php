<div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Báo cáo Tồn kho</h2>
            <p class="text-muted mb-0">Tổng quan về số lượng sản phẩm hiện có.</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam fs-2 mb-2"></i>
                    <h5 class="card-title"><?= count($data['inventoryData'] ?? []) ?></h5>
                    <p class="card-text">Sản phẩm trong kho</p>
                </div>
            </div>
        </div>
        <!-- Can add other summary cards here later, e.g., total value -->
    </div>

    <!-- Inventory Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Chi tiết Tồn kho</h5>
            <!-- Maybe a search bar here in the future -->
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th><i class="bi bi-hash"></i> Mã sản phẩm</th>
                            <th><i class="bi bi-journal-text"></i> Tên sản phẩm</th>
                            <th><i class="bi bi-box"></i> Số lượng còn lại</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['inventoryData'])): ?>
                            <?php foreach ($data['inventoryData'] as $product): ?>
                                <tr class="text-center">
                                    <td><strong><?= htmlspecialchars($product['masp']) ?></strong></td>
                                    <td class="text-start"><?= htmlspecialchars($product['tensp']) ?></td>
                                    <td><span class="badge bg-success rounded-pill"><?= htmlspecialchars($product['soluong']) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-5">
                                    <i class="bi bi-cloud-drizzle fs-1"></i>
                                    <p class="mt-2">Không có dữ liệu tồn kho.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
