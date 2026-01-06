<?php
require_once "./app/Controller.php";

class Report extends Controller {

    public function Show() {
        $reportModel = $this->model("ReportModel");

        $period = $_GET['period'] ?? 'daily'; // Lấy period từ GET request, mặc định là 'daily'
        
        $dataReport = [];
        $totalRevenue = 0;

        switch ($period) {
            case 'daily':
                $dataReport['daily'] = $reportModel->getRevenueReport('daily');
                $totalRevenue = array_sum(array_column($dataReport['daily'], 'total'));
                break;
            case 'monthly':
                $dataReport['monthly'] = $reportModel->getRevenueReport('monthly');
                $totalRevenue = array_sum(array_column($dataReport['monthly'], 'total'));
                break;
            case 'yearly':
                $dataReport['yearly'] = $reportModel->getRevenueReport('yearly');
                $totalRevenue = array_sum(array_column($dataReport['yearly'], 'total'));
                break;
            case 'specific':
                $day = $_GET['day'] ?? null;
                $month = $_GET['month'] ?? null;
                $year = $_GET['year'] ?? null;
                $dataReport['specific'] = $reportModel->getSpecificRevenueReport($day, $month, $year);
                $totalRevenue = array_sum(array_column($dataReport['specific'], 'total'));
                break;
            default:
                $dataReport['daily'] = $reportModel->getRevenueReport('daily');
                $totalRevenue = array_sum(array_column($dataReport['daily'], 'total'));
                $period = 'daily';
                break;
        }
        
        $dataReport['period'] = $period;
        $dataReport['totalRevenue'] = $totalRevenue;

        // Lấy dữ liệu báo cáo mới
        $dataReport['best_selling_products'] = $reportModel->getBestSellingProducts();
        $dataReport['slow_selling_products'] = $reportModel->getSlowSellingProducts();
        $dataReport['best_selling_categories'] = $reportModel->getBestSellingCategories();
        
        // Lấy dữ liệu tăng trưởng
        $growth_period = ($period == 'yearly') ? 'yearly' : 'monthly';
        $dataReport['revenue_growth'] = $reportModel->getRevenueGrowth($growth_period);
        $dataReport['growth_period_label'] = ($growth_period == 'monthly') ? 'so với tháng trước' : 'so với năm trước';


        $this->view("adminPage", [
            'page' => 'ReportView',
            'reportData' => $dataReport
        ]);
    }

    public function inventory() {
        $reportModel = $this->model("ReportModel");
        $inventoryData = $reportModel->getInventoryReport();

        $this->view("adminPage", [
            'page' => 'InventoryReportView',
            'inventoryData' => $inventoryData
        ]);
    }
}
