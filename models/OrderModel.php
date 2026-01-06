<?php
require_once 'BaseModel.php';
class OrderModel extends BaseModel {
    // Lấy chi tiết đơn hàng theo order_id
       protected $table = 'orders';

    public function createOrder($userId, $orderCode, $totalAmount) {
        $sql = "INSERT INTO $this->table (user_id, order_code, total_amount, created_at) VALUES (?, ?, ?, NOW())";
        $this->query($sql, [$userId, $orderCode, $totalAmount]);
        return $this->getLastInsertId();
    }
    // Lấy chi tiết đơn hàng theo order_id
    public function getOrderDetailsByOrderId($orderId) {
        $sql = "SELECT * FROM order_details WHERE order_id = ?";
        return $this->select($sql, [$orderId]);
    }

    public function getOrdersByUser($userId) {
        $sql = "SELECT * FROM $this->table WHERE user_id = ? ORDER BY created_at DESC";
        return $this->select($sql, [$userId]);
    }
     public function createOrderWithShipping(
        $userEmail, $orderCode, $totalAmount, 
        $receiver, $phone, $address,
        $shippingMethod, $shippingCost,
        $userId // Thêm userId vào tham số
    ) {
        // Thêm order_status vào câu lệnh INSERT
        $sql = "INSERT INTO $this->table (user_id, user_email, order_code, total_amount, shipping_method, shipping_cost, receiver, phone, address, created_at, order_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Chờ xác nhận')";
        $this->query($sql, [
            $userId, // Lưu user_id trực tiếp
            $userEmail, $orderCode, $totalAmount, $shippingMethod, $shippingCost, 
            $receiver, $phone, $address
        ]);
        return $this->getLastInsertId();
    }

      // Lấy lịch sử đơn hàng theo email
    public function getOrdersByEmail($email) {
        $sql = "SELECT * FROM $this->table WHERE user_email = ? ORDER BY created_at DESC";
        return $this->select($sql, [$email]);
    }
    public function getOrderById($orderId) {
    $sql = "SELECT * FROM {$this->table} WHERE id = ?";
    $rows = $this->select($sql, [$orderId]); // select() trả về mảng các row
    return !empty($rows) ? $rows[0] : null;   // trả về 1 row hoặc null nếu không có
    }
    public function getOrderByCode($orderCode) {
        $sql = "SELECT * FROM {$this->table} WHERE order_code = ?";
        $result = $this->select($sql, [$orderCode]);
        return $result[0] ?? null;
    }

    public function updateOrderStatus($orderId, $status) {
        // Cập nhật cột order_status thay vì transaction_info
        $sql = "UPDATE {$this->table} SET order_status = :status WHERE id = :orderId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updatePaymentStatus($orderId, $status) {
        // Phương thức này để cập nhật trạng thái thanh toán
        $sql = "UPDATE {$this->table} SET transaction_info = :status WHERE id = :orderId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Lấy tất cả đơn hàng cho admin
    public function getAllOrders($filters = []) {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        // Có thể thêm logic filter theo trạng thái, ngày tháng... ở đây
        return $this->select($sql);
    }
    
}