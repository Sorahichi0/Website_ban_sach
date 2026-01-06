<?php
class PromoCodeModel extends DB {
    private $table = "tbl_promo_codes";

    public function getAll($searchTerm = '') {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($searchTerm)) {
            $sql .= " WHERE code LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $sql .= " ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($code, $type, $value, $min_order_value, $start_date, $end_date, $usage_limit, $status) {
        $sql = "INSERT INTO {$this->table} (code, type, value, min_order_value, start_date, end_date, usage_limit, status) 
                VALUES (:code, :type, :value, :min_order_value, :start_date, :end_date, :usage_limit, :status)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':min_order_value', $min_order_value);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':usage_limit', $usage_limit);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function update($id, $code, $type, $value, $min_order_value, $start_date, $end_date, $usage_limit, $status) {
        $sql = "UPDATE {$this->table} SET 
                code = :code, 
                type = :type, 
                value = :value, 
                min_order_value = :min_order_value,
                start_date = :start_date, 
                end_date = :end_date, 
                usage_limit = :usage_limit,
                status = :status 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':min_order_value', $min_order_value);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':usage_limit', $usage_limit);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Tìm và xác thực mã khuyến mại cho người dùng
     * @param string $code
     * @param float $currentOrderValue
     * @return array|false
     */
    public function findAndValidateCode($code, $currentOrderValue) {
        $sql = "SELECT * FROM {$this->table} WHERE code = :code AND status = 1 LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        $promo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$promo) {
            return ['valid' => false, 'message' => 'Voucher không tồn tại hoặc đã bị vô hiệu hóa.'];
        }

        $now = new DateTime();
        $startDate = new DateTime($promo['start_date']);
        $endDate = new DateTime($promo['end_date']);

        if ($now < $startDate || $now > $endDate) {
            return ['valid' => false, 'message' => 'Voucher đã hết hạn hoặc chưa có hiệu lực.'];
        }

        // Kiểm tra giới hạn sử dụng
        if ($promo['usage_limit'] > 0 && $promo['usage_count'] >= $promo['usage_limit']) {
            return ['valid' => false, 'message' => 'Voucher đã hết lượt sử dụng.'];
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($currentOrderValue < $promo['min_order_value']) {
            return ['valid' => false, 'message' => 'Voucher chỉ áp dụng cho đơn hàng từ ' . number_format($promo['min_order_value']) . ' ₫.'];
        }

        return ['valid' => true, 'promo' => $promo];
    }

    // Tăng số lần đã sử dụng của voucher
    public function incrementUsageCount($code) {
        $sql = "UPDATE {$this->table} SET usage_count = usage_count + 1 WHERE code = :code";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['code' => $code]);
    }

    /**
     * Lấy tất cả các mã khuyến mại đang có hiệu lực
     * @return array
     */
    public function getAvailableCodes() {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 1 
                AND start_date <= :now 
                AND end_date >= :now 
                ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':now', $now);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}