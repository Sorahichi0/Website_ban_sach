<?php
require_once "./app/DB.php";

class CommentModel extends DB {
    private $table = 'comments';

    /**
     * Tạo một bình luận mới (mặc định là chờ duyệt)
     */
    public function create($productId, $userId, $rating, $content) {
        $sql = "INSERT INTO {$this->table} (product_id, user_id, rating, content, status) VALUES (:product_id, :user_id, :rating, :content, 0)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':product_id' => $productId,
            ':user_id' => $userId,
            ':rating' => $rating,
            ':content' => $content
        ]);
    }

    /**
     * Lấy các bình luận đã được duyệt của một sản phẩm
     */
    public function getApprovedByProductId($productId) {
        $sql = "SELECT c.*, u.fullname as user_name 
                FROM {$this->table} c
                JOIN tbluser u ON c.user_id = u.user_id
                WHERE c.product_id = :product_id AND c.status = 1
                ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả bình luận cho trang admin
     */
    public function getAll() {
        $sql = "SELECT c.*, u.fullname as user_name, p.tensp as product_name
                FROM {$this->table} c
                JOIN tbluser u ON c.user_id = u.user_id
                JOIN tblsanpham p ON c.product_id = p.masp
                ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật trạng thái (duyệt/bỏ duyệt)
     */
    public function updateStatus($commentId, $status) {
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $commentId]);
    }

    /**
     * Xóa một bình luận
     */
    public function delete($commentId) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $commentId]);
    }
}