<?php
class AdminUserController extends Controller {

    private $userModel;

    public function __construct() {
        $this->userModel = $this->model("UserModel");

        // Kiểm tra xem người dùng đã đăng nhập và có vai trò hợp lệ chưa
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role_name'])) {
            header('Location: ' . APP_URL . '/AdminAuthController/showLogin');
            exit();
        }

        // Chỉ SuperAdmin mới có quyền truy cập controller này
        if ($_SESSION['user']['role_name'] !== 'SuperAdmin') {
            // Chuyển hướng những vai trò khác về trang dashboard chính
            header('Location: ' . APP_URL . '/Product/show');
            exit();
        }
    }

    public function show() {
        $admins = $this->userModel->getAdminAccounts();
        $this->view("adminPage", [
            "page" => "AdminUserListView",
            "admins" => $admins
        ]);
    }

    public function create() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $password = $_POST['password'];
            // Tạo user mới với vai trò được chọn
            $user = $this->model('UserModel');
            $user->fullname = $_POST['fullname'];
            $user->email = $_POST['email'];
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            
            // Cần sửa lại phương thức create để nhận role_id
            // Tạm thời sẽ tạo rồi update
            $user->create(); 
            $newUser = $user->findByEmail($_POST['email']);
            $this->userModel->updateUser($newUser['user_id'], $_POST['fullname'], '', '', $_POST['role_id']);

            header("Location: " . APP_URL . "/AdminUserController/show");
            exit();
        } else {
            $roles = $this->userModel->getAllRoles();
            $this->view("adminPage", [
                "page" => "AdminUserFormView",
                "roles" => array_filter($roles, fn($r) => $r['role_name'] !== 'Customer') // Chỉ hiển thị vai trò admin
            ]);
        }
    }

    public function edit($id) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->userModel->updateUser($id, $_POST['fullname'], $_POST['phone'], $_POST['address'], $_POST['role_id']);
            
            // Nếu có nhập mật khẩu mới thì cập nhật
            if (!empty($_POST['password'])) {
                $newPasswordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $this->userModel->updatePassword($this->userModel->getUserByUserId($id)['email'], $newPasswordHash);
            }

            header("Location: " . APP_URL . "/AdminUserController/show");
            exit();
        } else {
            $admin = $this->userModel->getUserByUserId($id);
            $roles = $this->userModel->getAllRoles();
            $this->view("adminPage", [
                "page" => "AdminUserFormView", 
                "editItem" => $admin,
                "roles" => array_filter($roles, fn($r) => $r['role_name'] !== 'Customer')
            ]);
        }
    }

    public function delete($id) {
        $this->userModel->deleteUser($id);
        header("Location: " . APP_URL . "/AdminUserController/show");
        exit();
    }
}
?>