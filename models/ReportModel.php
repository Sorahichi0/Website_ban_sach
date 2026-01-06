<?php
require_once "./models/BaseModel.php";

class ReportModel extends BaseModel {

    public function getSpecificRevenueReport($day, $month, $year) {
        $sql = "
            SELECT
                DATE(o.created_at) AS date,
                SUM(o.total_amount) AS total
            FROM orders o
            WHERE o.transaction_info = 'dathanhtoan'";

        $params = [];
        if (!empty($year)) {
            $sql .= " AND YEAR(o.created_at) = ?";
            $params[] = $year;
        }
        if (!empty($month)) {
            $sql .= " AND MONTH(o.created_at) = ?";
            $params[] = $month;
        }
        if (!empty($day)) {
            $sql .= " AND DAY(o.created_at) = ?";
            $params[] = $day;
        }

        $sql .= " GROUP BY DATE(o.created_at) ORDER BY DATE(o.created_at) ASC";
        
        return $this->select($sql, $params);
    }

    /**
     * Lấy dữ liệu doanh thu
     * @param string $type: 'date', 'month', 'year'
     * @param string $day  : ngày hiện tại (YYYY-MM-DD)
     * @param string $month: tháng hiện tại (YYYY-MM)
     * @param string $year : năm hiện tại (YYYY)
     * @return array
     */
    public function getRevenueReport($period = 'daily') {
        $sql = "";
        $results = [];

        switch ($period) {
            case 'daily':
                $sql = "
                    SELECT
                        DATE(o.created_at) AS date,
                        SUM(o.total_amount) AS total
                    FROM orders o
                    WHERE o.transaction_info = 'dathanhtoan'
                    GROUP BY DATE(o.created_at)
                    ORDER BY DATE(o.created_at) ASC
                ";
                $results = $this->select($sql);
                break;
            case 'monthly':
                $sql = "
                    SELECT
                        DATE_FORMAT(o.created_at, '%Y-%m') AS month,
                        SUM(o.total_amount) AS total
                    FROM orders o
                    WHERE o.transaction_info = 'dathanhtoan'
                    GROUP BY DATE_FORMAT(o.created_at, '%Y-%m')
                    ORDER BY DATE_FORMAT(o.created_at, '%Y-%m') ASC
                ";
                $results = $this->select($sql);
                break;
            case 'yearly':
                $sql = "
                    SELECT
                        DATE_FORMAT(o.created_at, '%Y') AS year,
                        SUM(o.total_amount) AS total
                    FROM orders o
                    WHERE o.transaction_info = 'dathanhtoan'
                    GROUP BY DATE_FORMAT(o.created_at, '%Y')
                    ORDER BY DATE_FORMAT(o.created_at, '%Y') ASC
                ";
                $results = $this->select($sql);
                break;
            default:
                // Default to daily if an invalid period is provided
                $sql = "
                    SELECT
                        DATE(o.created_at) AS date,
                        SUM(o.total_amount) AS total
                    FROM orders o
                    WHERE o.transaction_info = 'dathanhtoan'
                    GROUP BY DATE(o.created_at)
                    ORDER BY DATE(o.created_at) ASC
                ";
                $results = $this->select($sql);
                break;
        }
        return $results;
    }

    public function getInventoryReport() {
        $sql = "
            SELECT 
                masp, 
                tensp, 
                soluong 
            FROM tblsanpham 
            ORDER BY tensp ASC
        ";
        return $this->select($sql);
    }

    /**
     * Lấy sản phẩm bán chạy nhất
     */
    public function getBestSellingProducts($limit = 10) {
        $sql = "
            SELECT 
                p.masp, 
                p.tensp, 
                SUM(od.quantity) as total_quantity_sold
            FROM order_details od
            JOIN tblsanpham p ON od.product_id = p.masp
            JOIN orders o ON od.order_id = o.id
            WHERE o.transaction_info = 'dathanhtoan'
            GROUP BY p.masp, p.tensp
            ORDER BY total_quantity_sold DESC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy sản phẩm bán chậm nhất (bao gồm cả sản phẩm chưa bán được)
     */
    public function getSlowSellingProducts($limit = 10) {
        $sql = "
            SELECT 
                p.masp, 
                p.tensp, 
                COALESCE(SUM(od.quantity), 0) as total_quantity_sold
            FROM tblsanpham p
            LEFT JOIN order_details od ON p.masp = od.product_id
            LEFT JOIN orders o ON od.order_id = o.id AND o.transaction_info = 'dathanhtoan'
            GROUP BY p.masp, p.tensp
            ORDER BY total_quantity_sold ASC, p.tensp ASC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh mục bán chạy nhất
     */
    public function getBestSellingCategories($limit = 5) {
        $sql = "
            SELECT 
                c.maLoaiSP, 
                c.tenLoaiSP, 
                SUM(od.quantity) as total_quantity_sold
            FROM order_details od
            JOIN tblsanpham p ON od.product_id = p.masp
            JOIN tblloaisp c ON p.maLoaiSP = c.maLoaiSP
            JOIN orders o ON od.order_id = o.id
            WHERE o.transaction_info = 'dathanhtoan'
            GROUP BY c.maLoaiSP, c.tenLoaiSP
            ORDER BY total_quantity_sold DESC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tính toán tăng trưởng doanh thu so với kỳ trước
     */
    public function getRevenueGrowth($period = 'monthly') {
        if ($period == 'monthly') {
            $current_period_sql = "SELECT SUM(total_amount) as total FROM orders WHERE transaction_info = 'dathanhtoan' AND YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())";
            $previous_period_sql = "SELECT SUM(total_amount) as total FROM orders WHERE transaction_info = 'dathanhtoan' AND YEAR(created_at) = YEAR(CURDATE() - INTERVAL 1 MONTH) AND MONTH(created_at) = MONTH(CURDATE() - INTERVAL 1 MONTH)";
        } else { // yearly
            $current_period_sql = "SELECT SUM(total_amount) as total FROM orders WHERE transaction_info = 'dathanhtoan' AND YEAR(created_at) = YEAR(CURDATE())";
            $previous_period_sql = "SELECT SUM(total_amount) as total FROM orders WHERE transaction_info = 'dathanhtoan' AND YEAR(created_at) = YEAR(CURDATE()) - 1";
        }

        $current_revenue = $this->select($current_period_sql)[0]['total'] ?? 0;
        $previous_revenue = $this->select($previous_period_sql)[0]['total'] ?? 0;

        return ['current' => $current_revenue, 'previous' => $previous_revenue];
    }
}
