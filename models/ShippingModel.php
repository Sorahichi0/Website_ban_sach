<?php
require_once 'BaseModel.php';

class ShippingModel extends BaseModel {
    private $table = 'shipping_methods';

    /**
     * Lấy tất cả các phương thức vận chuyển đang hoạt động.
     * @return array
     */
    public function getActiveMethods() {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY cost ASC";
        return $this->select($sql);
    }

    /**
     * Lấy thông tin một phương thức vận chuyển theo ID.
     * @param int $id
     * @return array|null
     */
    public function getMethodById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->select($sql, [$id]);
        return $result[0] ?? null;
    }
}