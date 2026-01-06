<?php
class AdminAuthController extends Controller {

    private $userModel;

    public function __construct() {
        $this->userModel = $this->model("UserModel");
    }

    /**
     * Hiển thị form đăng nhập cho admin
     */
    public function showLogin() {
        // Nếu admin đã đăng nhập, chuyển hướng tới trang dashboard
        if (isset($_SESSION['user']) && in_array($_SESSION['user']['role_name'], ['SuperAdmin', 'Admin'])) {
            header('Location: ' . APP_URL . '/Product/show');
            exit();
        }
        $this->view("AdminLoginView");
    }

    /**
     * Xử lý logic đăng nhập
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Kiểm tra vai trò
                if (in_array($user['role_name'], ['SuperAdmin', 'Admin'])) {
                    // Đăng nhập thành công, lưu thông tin vào session
                    $_SESSION['user'] = [
                        'id' => $user['user_id'],
                        'fullname' => $user['fullname'],
                        'email' => $user['email'],
                        'role_name' => $user['role_name']
                    ];
                    header('Location: ' . APP_URL . '/Product/show'); // Chuyển hướng đến trang quản trị
                    exit();
                }
            }

            // Đăng nhập thất bại
            $this->view("AdminLoginView", ['error' => 'Email, mật khẩu hoặc vai trò không hợp lệ.']);
        } else {
            header('Location: ' . APP_URL . '/AdminAuthController/showLogin');
            exit();
        }
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout() {
        session_destroy();
        header('Location: ' . APP_URL . '/AdminAuthController/showLogin');
        exit();
    }
}