<?php
require_once "./app/Controller.php";

class CustomerController extends Controller {

    private $userModel;

    public function __construct() {
        $this->userModel = $this->model("UserModel");
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role_name'], ['SuperAdmin', 'Admin'])) {
            header('Location: ' . APP_URL . '/AdminAuthController/showLogin');
            exit();
        }
    }

    // Hiển thị danh sách khách hàng, hỗ trợ tìm kiếm theo số điện thoại
    public function show() {
    $searchKeyword = $_GET['phone_search'] ?? '';

    if (!empty($searchKeyword)) {
        $customers = $this->userModel->searchUserByPhone($searchKeyword);
    } else {
        $customers = $this->userModel->getAllUsers();
    }

    $this->view("adminPage", [
        'page' => 'CustomerListView',
        'customers' => $customers,
        'searchKeyword' => $searchKeyword
    ]);
    }

    // Trang chỉnh sửa thông tin khách hàng
    public function edit($user_id) {
        $customer = $this->userModel->getUserByUserId($user_id);
        if (!$customer) {
            // Nếu khách hàng không tồn tại, quay lại danh sách
            header('Location: ' . APP_URL . '/CustomerController/show');
            exit();
        }

        $this->view("adminPage", [
            'page' => 'CustomerEditView',
            'customer' => $customer
        ]);
    }

    // Cập nhật thông tin khách hàng
    public function update($user_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullname = $_POST['fullname'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';

            if ($this->userModel->updateUser($user_id, $fullname, $phone, $address)) {
                header('Location: ' . APP_URL . '/CustomerController/show?message=update_success');
                exit();
            } else {
                header('Location: ' . APP_URL . '/CustomerController/edit/' . $user_id . '?message=update_error');
                exit();
            }
        } else {
            header('Location: ' . APP_URL . '/CustomerController/edit/' . $user_id);
            exit();
        }
    }

    // Xóa khách hàng
    public function delete($user_id) {
        if ($this->userModel->deleteUser($user_id)) {
            header('Location: ' . APP_URL . '/CustomerController/show?message=delete_success');
            exit();
        } else {
            header('Location: ' . APP_URL . '/CustomerController/show?message=delete_error');
            exit();
        }
    }

    // Khóa hoặc mở khóa tài khoản
    public function toggleStatus($user_id) {
        $customer = $this->userModel->getUserByUserId($user_id);
        if ($customer) {
            // Đảo ngược trạng thái: nếu đang active (1) thì thành locked (0) và ngược lại
            $new_status = $customer['is_active'] ? 0 : 1;
            $this->userModel->updateUserStatus($user_id, $new_status);
        }
        header('Location: ' . APP_URL . '/CustomerController/show');
        exit();
    }
}
