<?php
require_once "./models/BaseModel.php";
class ProductModel extends BaseModel
{
    const TABLE = 'tblsanpham';

    public function getAll($select = ['*'], $orderBy = [], $limit = 15)
    {
        $columns = implode(', ', $select);
        $sql = "SELECT {$columns} FROM " . self::TABLE;

        if (!empty($orderBy)) {
            $orderByClauses = [];
            foreach ($orderBy as $column => $direction) {
                $orderByClauses[] = "{$column} {$direction}";
            }
            $sql .= " ORDER BY " . implode(', ', $orderByClauses);
        }

        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }

        return $this->select($sql);
    }

    public function findById($id)
    {
        return $this->find(self::TABLE, $id);
    }

    public function findByProductType($productTypeId)
    {
        $sql = "SELECT * FROM " . self::TABLE . " WHERE maLoaiSP = ?";
        return $this->query($sql, [$productTypeId]);
    }

    public function getProductTypes()
    {
        $sql = "SELECT maLoaiSP, tenLoaiSP FROM tblloaisp";
        return $this->query($sql);
    }
    
    public function getPublishers()
    {
        $sql = "SELECT DISTINCT publisher FROM " . self::TABLE . " WHERE publisher IS NOT NULL AND publisher != 'Đang cập nhật' ORDER BY publisher ASC";
        return $this->query($sql);
    }

    /**
     * Hàm tìm kiếm nâng cao
     * @param array $filters Các tiêu chí lọc
     * @return array Danh sách sản phẩm
     */
    public function getAdvancedSearch($filters = [])
    {
        // DEBUG: Simplified query to isolate the issue.
        $sql = "SELECT 
            p.*, 
            l.tenLoaiSP,
            (p.giaXuat * (1 - p.khuyenmai / 100)) as discounted_price,
            COALESCE(od.sold_count,0) as sold_count,
            AVG(c.rating) as avg_rating
        FROM tblsanpham p
        LEFT JOIN tblloaisp l ON p.maLoaiSP = l.maLoaiSP
        LEFT JOIN (
            SELECT product_id, SUM(quantity) as sold_count
            FROM order_details
            GROUP BY product_id
        ) od ON p.masp = od.product_id
        LEFT JOIN comments c ON p.masp = c.product_id AND c.status = 1"; // chỉ tính rating đã duyệt

        $where = [];
        $params = [];

        // 1. Lọc theo tên sách (tensp) - case-insensitive
        if (!empty($filters['tensp'])) {
            $where[] = "LOWER(p.tensp) LIKE ?";
            $params[] = '%' . mb_strtolower($filters['tensp'], 'UTF-8') . '%';
        }

        // 2. Lọc theo tác giả (author) - case-insensitive
        if (!empty($filters['author'])) {
            $where[] = "LOWER(p.author) LIKE ?";
            $params[] = '%' . mb_strtolower($filters['author'], 'UTF-8') . '%';
        }

        // 3. Lọc theo thể loại (maLoaiSP)
        if (!empty($filters['maLoaiSP'])) {
            // Lấy cả loại con
            $allIds = $this->getCategoryWithChildren($filters['maLoaiSP']);
            $placeholders = implode(',', array_fill(0, count($allIds), '?'));
            $where[] = "p.maLoaiSP IN ($placeholders)";
            $params = array_merge($params, $allIds);
        }

        // 4. Lọc theo nhà xuất bản (publisher)
        if (!empty($filters['publisher'])) {
            $where[] = "p.publisher = ?";
            $params[] = $filters['publisher'];
        }

        // 5. Lọc theo khoảng giá (min_price, max_price)
        if (!empty($filters['min_price'])) {
            $where[] = "p.giaXuat >= ?";
            $params[] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = "p.giaXuat <= ?";
            $params[] = $filters['max_price'];
        }
        
        // Note: Rating filter is disabled during debugging.

        // Ghép các điều kiện WHERE
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " GROUP BY p.masp"; // quan trọng để AVG(c.rating) hoạt động
        // Sắp xếp kết quả
        $sql .= " ORDER BY p.createDate DESC";

        return $this->select($sql, $params);
    }
    // Lấy tất cả id của loại và con của nó
    public function getCategoryWithChildren($maLoaiSP)
    {
        $ids = [$maLoaiSP]; // include chính nó

        // Lấy các loại con
        $sql = "SELECT maLoaiSP FROM tblloaisp WHERE parent_id = ?";
        $children = $this->select($sql, [$maLoaiSP]);

        foreach ($children as $c) {
            $ids[] = $c['maLoaiSP'];
        }

        return $ids; // trả về mảng maLoaiSP
    }
    public function updateProductQuantity($productId, $quantity)
    {
        $sql = "UPDATE " . self::TABLE . " SET soluong = soluong - ? WHERE masp = ?";
        return $this->query($sql, [$quantity, $productId]);
    }
}