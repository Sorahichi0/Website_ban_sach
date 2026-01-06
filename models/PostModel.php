<?php
require_once "./app/DB.php";


class PostModel extends DB {

    // Lấy tất cả bài viết
    public function getAllPosts() {
        $sql = "SELECT * FROM posts ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy bài viết theo ID
    public function getPostById($id) {
        $sql = "SELECT * FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm bài viết
    public function createPost($title, $content, $image, $status) {
        $sql = "INSERT INTO posts (title, content, image, status, created_at)
                VALUES (:title, :content, :image, :status, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'title' => $title,
            'content' => $content,
            'image' => $image,
            'status' => $status
        ]);
    }

    // Cập nhật bài viết
    public function updatePost($id, $title, $content, $image, $status) {
        $sql = "UPDATE posts 
                SET title = :title, content = :content, image = :image, status = :status 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'image' => $image,
            'status' => $status
        ]);
    }

    // Xóa bài viết
    public function deletePost($id) {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    public function getVisiblePosts() {
    $sql = "SELECT * FROM posts WHERE status = 1 ORDER BY id DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


