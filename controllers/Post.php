<?php
require_once './app/BaseController.php';
class Post extends BaseController {

    public function show() {
        $postModel = $this->model("PostModel");
        $posts = $postModel->getAllPosts();

        $this->view("adminPage", [
            "page" => "PostListView",
            "postList" => $posts
        ]);
    }

    public function create() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tieude = $_POST["txt_tieude"] ?? '';
            $noidung = $_POST["txt_noidung"] ?? '';
            $trangthai = isset($_POST["txt_trangthai"]) ? 1 : 0;

            $hinhanh = "";
            if (isset($_FILES["uploadfile"]) && $_FILES["uploadfile"]["error"] == 0) {
                $target_dir = "./public/images/";
                $hinhanh = uniqid() . "-" . basename($_FILES["uploadfile"]["name"]);
                $target_file = $target_dir . $hinhanh;
                move_uploaded_file($_FILES["uploadfile"]["tmp_name"], $target_file);
            }

            $postModel = $this->model("PostModel");
            $postModel->createPost($tieude, $noidung, $hinhanh, $trangthai);

            header("Location: " . APP_URL . "/Post/show");
            exit();
        } else {
            $this->view("adminPage", ["page" => "PostCreate"]);
        }
    }

    public function edit($id) {
        $postModel = $this->model("PostModel");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tieude = $_POST["txt_tieude"] ?? '';
            $noidung = $_POST["txt_noidung"] ?? '';
            $trangthai = isset($_POST["txt_trangthai"]) ? 1 : 0;

            $hinhanh = $_POST['old_image'];
            if (isset($_FILES["uploadfile"]) && $_FILES["uploadfile"]["error"] == 0) {
                if (!empty($hinhanh) && file_exists("./public/images/" . $hinhanh)) {
                    unlink("./public/images/" . $hinhanh);
                }
                $target_dir = "./public/images/";
                $hinhanh = uniqid() . "-" . basename($_FILES["uploadfile"]["name"]);
                $target_file = $target_dir . $hinhanh;
                move_uploaded_file($_FILES["uploadfile"]["tmp_name"], $target_file);
            }

            $postModel->updatePost($id, $tieude, $noidung, $hinhanh, $trangthai);
            header("Location: " . APP_URL . "/Post/show");
            exit();
        } else {
            $post = $postModel->getPostById($id);
            $this->view("adminPage", [
                "page" => "PostEdit",
                "editItem" => $post
            ]);
        }
    }

    public function delete($id) {
        $postModel = $this->model("PostModel");
        $postModel->deletePost($id);
        header("Location: " . APP_URL . "/Post/show");
        exit();
    }
    public function index() {
        $postModel = $this->model("PostModel");
        $posts = $postModel->getVisiblePosts();

        $this->view("Font_end/PostPublicList", [
            "posts" => $posts
        ]);
    }
    public function detail($id) {
        $postModel = $this->model("PostModel");
        $post = $postModel->getPostById($id);

        // Kiểm tra xem bài viết có hiển thị không
        if (!$post || $post["status"] == 0) {
            echo "<h3 class='text-center mt-5 text-danger'>Bài viết không tồn tại hoặc đã bị ẩn!</h3>";
            return;
        }
        $this->view("Font_end/PostPublicDetail", [
            "post" => $post
        ]);
    }
}
