<?php
// ReportView.php
$reportData = $data['reportData'];
$period = $reportData['period'] ?? 'daily';
$totalRevenue = $reportData['totalRevenue'] ?? 0;

$chartLabels = [];
$chartValues = [];
$tableData = [];
$tableHeaders = [];

// Dữ liệu báo cáo mới
$bestSellingProducts = $reportData['best_selling_products'] ?? [];
$slowSellingProducts = $reportData['slow_selling_products'] ?? [];
$bestSellingCategories = $reportData['best_selling_categories'] ?? [];
$revenueGrowth = $reportData['revenue_growth'] ?? ['current' => 0, 'previous' => 0];
$growthPeriodLabel = $reportData['growth_period_label'] ?? '';

$growthPercentage = 0;
if ($revenueGrowth['previous'] > 0) {
    $growthPercentage = (($revenueGrowth['current'] - $revenueGrowth['previous']) / $revenueGrowth['previous']) * 100;
}

switch($period) {
    case 'daily':
        $tableData = $reportData['daily'] ?? [];
        $tableHeaders = ['Ngày', 'Doanh thu'];
        $chartLabels = array_column($tableData, 'date');
        $chartValues = array_column($tableData, 'total');
        $chartTitle = 'Doanh thu theo ngày';
        $chartColor = 'rgba(54, 162, 235, 0.6)';
        break;
    case 'monthly':
        $tableData = $reportData['monthly'] ?? [];
        $tableHeaders = ['Tháng', 'Doanh thu'];
        $chartLabels = array_column($tableData, 'month');
        $chartValues = array_column($tableData, 'total');
        $chartTitle = 'Doanh thu theo tháng';
        $chartColor = 'rgba(255, 206, 86, 0.6)';
        break;
    case 'yearly':
        $tableData = $reportData['yearly'] ?? [];
        $tableHeaders = ['Năm', 'Doanh thu'];
        $chartLabels = array_column($tableData, 'year');
        $chartValues = array_column($tableData, 'total');
        $chartTitle = 'Doanh thu theo năm';
        $chartColor = 'rgba(75, 192, 192, 0.6)';
        break;
    case 'specific':
        $tableData = $reportData['specific'] ?? [];
        $tableHeaders = ['Ngày', 'Doanh thu'];
        $chartLabels = array_column($tableData, 'date');
        $chartValues = array_column($tableData, 'total');
        $chartTitle = 'Doanh thu theo ngày cụ thể';
        $chartColor = 'rgba(153, 102, 255, 0.6)';
        break;
}
?>

<div class="container py-4">

    <!-- Header và bộ lọc -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary mb-0"><i class="bi bi-graph-up-arrow"></i> Báo cáo doanh thu</h3>
    </div>

    <!-- Các thẻ thống kê tổng quan -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-cash-coin"></i> Tổng Doanh Thu</h5>
                    <p class="card-text fs-4 fw-bold"><?= number_format($totalRevenue) ?> ₫</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card text-white <?= $growthPercentage >= 0 ? 'bg-info' : 'bg-danger' ?> shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-graph-up"></i> Tăng Trưởng</h5>
                    <p class="card-text fs-4 fw-bold">
                        <?= ($growthPercentage >= 0 ? '+' : '') . number_format($growthPercentage, 2) ?>%
                    </p>
                    <small><?= $growthPeriodLabel ?></small>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                     <h5 class="card-title"><i class="bi bi-calendar-event"></i> Lọc Doanh Thu</h5>
                     <form method="get" action="<?= APP_URL ?>/Report/show" class="d-flex align-items-end">
                        <div class="flex-grow-1">
                            <label for="period" class="form-label small">Xem theo:</label>
                            <select name="period" id="period" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="daily" <?= $period == 'daily' ? 'selected' : '' ?>>30 ngày qua</option>
                                <option value="monthly" <?= $period == 'monthly' ? 'selected' : '' ?>>12 tháng qua</option>
                                <option value="yearly" <?= $period == 'yearly' ? 'selected' : '' ?>>Theo năm</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ và bảng doanh thu chi tiết -->
    <div class="row mb-4">
        <div class="col-12">
             <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>Biểu đồ: <?= htmlspecialchars($chartTitle) ?></strong>
                </div>
                <div class="card-body">
                    <canvas id="reportChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Báo cáo sản phẩm và danh mục -->
    <div class="row">
        <!-- Sản phẩm bán chạy -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-trophy-fill"></i> Top 10 Sản phẩm bán chạy
                </div>
                <div class="list-group list-group-flush">
                    <?php if (!empty($bestSellingProducts)): foreach ($bestSellingProducts as $item): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-truncate" title="<?= htmlspecialchars($item['tensp']) ?>"><?= htmlspecialchars($item['tensp']) ?></span>
                            <span class="badge bg-primary rounded-pill"><?= $item['total_quantity_sold'] ?></span>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="list-group-item text-muted">Chưa có dữ liệu.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sản phẩm bán chậm -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <i class="bi bi-cone-striped"></i> Top 10 Sản phẩm bán chậm
                </div>
                <div class="list-group list-group-flush">
                     <?php if (!empty($slowSellingProducts)): foreach ($slowSellingProducts as $item): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-truncate" title="<?= htmlspecialchars($item['tensp']) ?>"><?= htmlspecialchars($item['tensp']) ?></span>
                            <span class="badge bg-secondary rounded-pill"><?= $item['total_quantity_sold'] ?></span>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="list-group-item text-muted">Chưa có dữ liệu.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Danh mục bán chạy -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-tags-fill"></i> Top 5 Danh mục bán chạy
                </div>
                <div class="list-group list-group-flush">
                     <?php if (!empty($bestSellingCategories)): foreach ($bestSellingCategories as $item): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-truncate" title="<?= htmlspecialchars($item['tenLoaiSP']) ?>"><?= htmlspecialchars($item['tenLoaiSP']) ?></span>
                            <span class="badge bg-info rounded-pill"><?= $item['total_quantity_sold'] ?></span>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="list-group-item text-muted">Chưa có dữ liệu.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bộ lọc theo ngày cụ thể (ẩn đi, có thể dùng sau) -->
    <!--
    <form method="get" action="<?= APP_URL ?>/Report/show" class="d-flex align-items-center">
            <label for="period" class="me-2 mb-0">Xem theo:</label>
            <select name="period" id="period" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                <option value="daily" <?= $period == 'daily' ? 'selected' : '' ?>>Ngày</option>
                <option value="monthly" <?= $period == 'monthly' ? 'selected' : '' ?>>Tháng</option>
                <option value="yearly" <?= $period == 'yearly' ? 'selected' : '' ?>>Năm</option>
                <option value="specific" <?= $period == 'specific' ? 'selected' : '' ?>>Ngày cụ thể</option>
            </select>

            <div id="specific_date_inputs" class="d-flex align-items-center" style="display: <?= $period == 'specific' ? 'flex' : 'none' ?>;">
                <input type="number" name="day" class="form-control form-control-sm me-1" placeholder="Ngày" min="1" max="31" value="<?= $_GET['day'] ?? '' ?>">
                <input type="number" name="month" class="form-control form-control-sm me-1" placeholder="Tháng" min="1" max="12" value="<?= $_GET['month'] ?? '' ?>">
                <input type="number" name="year" class="form-control form-control-sm me-2" placeholder="Năm" min="2000" value="<?= $_GET['year'] ?? '' ?>">
            </div>

             <input type="submit" value="Xem" class="btn btn-sm btn-primary">
        </form>
    -->
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const labels = <?= json_encode($chartLabels) ?>;
        const data = <?= json_encode($chartValues) ?>;

        if (document.getElementById('reportChart')) {
            new Chart(document.getElementById('reportChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Doanh thu (VND)',
                        data: data,
                        backgroundColor: '<?= $chartColor ?>',
                        borderColor: '<?= str_replace('0.6','1',$chartColor) ?>',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value, index, values) {
                                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>