<?php
require_once 'BaseModel.php';

class CartModel extends BaseModel {
    private $table = 'cart';

    /**
     * Lấy tất cả sản phẩm trong giỏ hàng của người dùng.
     * @param int $userId
     * @return array
     */
    public function getCartByUserId($userId) {
        $sql = "SELECT 
                    c.quantity as qty,
                    p.masp,
                    p.tensp,
                    p.hinhanh,
                    p.giaXuat as giaxuat,
                    p.khuyenmai
                FROM {$this->table} c
                JOIN tblsanpham p ON c.masp = p.masp
                WHERE c.user_id = ?";
        error_log("SQL: " . $sql);
        error_log("Params: " . json_encode([$userId]));
        $items = $this->select($sql, [$userId]);

        // Chuyển đổi mảng về dạng key là masp để tương thích với code cũ
        $cart = [];
        foreach ($items as $item) {
            $cart[$item['masp']] = $item;
        }
        error_log("Cart data: " . json_encode($cart));
        return $cart;
    }

    /**
     * Thêm sản phẩm vào giỏ hàng hoặc cập nhật số lượng nếu đã tồn tại.
     * @param int $userId
     * @param string $productId
     * @param int $quantity
     * @return bool
     */
    public function addToCart($userId, $productId, $quantity) {
        $sql = "INSERT INTO {$this->table} (user_id, masp, quantity) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";
        return $this->query($sql, [$userId, $productId, $quantity]);
    }

    /**
     * Cập nhật số lượng của một sản phẩm trong giỏ hàng.
     * @param int $userId
     * @param string $productId
     * @param int $quantity
     * @return bool
     */
    public function updateQuantity($userId, $productId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeFromCart($userId, $productId);
        }
        $sql = "UPDATE {$this->table} SET quantity = ? WHERE user_id = ? AND masp = ?";
        return $this->query($sql, [$quantity, $userId, $productId]);
    }

    /**
     * Xóa một sản phẩm khỏi giỏ hàng.
     */
    public function removeFromCart($userId, $productId) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND masp = ?";
        return $this->query($sql, [$userId, $productId]);
    }

    /**
     * Xóa toàn bộ giỏ hàng của người dùng (sau khi đặt hàng).
     */
    public function clearCart($userId) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = ?";
        return $this->query($sql, [$userId]);
    }
}