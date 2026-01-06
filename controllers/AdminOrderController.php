<?php
class AdminOrderController extends Controller {

    private $orderModel;
    private $orderDetailModel;

    public function __construct() {
        $this->orderModel = $this->model("OrderModel");
        $this->orderDetailModel = $this->model("OrderDetailModel");
        // Giả sử có hàm kiểm tra quyền admin
        // if (!isAdmin()) { header('Location: ...'); exit(); }
    }

    // Hiển thị danh sách tất cả đơn hàng
    public function show() {
        $orders = $this->orderModel->getAllOrders();
        $this->view("adminPage", [
            "page" => "AdminOrderListView",
            "orders" => $orders
        ]);
    }

    // Hiển thị chi tiết đơn hàng và form cập nhật trạng thái
    public function detail($orderId) {
        $order = $this->orderModel->getOrderById($orderId);
        $details = $this->orderDetailModel->getOrderDetails($orderId);

        if (!$order) {
            // Xử lý khi không tìm thấy đơn hàng
            header("Location: " . APP_URL . "/AdminOrderController/show");
            exit();
        }

        $this->view("adminPage", [
            "page" => "AdminOrderDetailView",
            "order" => $order,
            "details" => $details
        ]);
    }

    // Xử lý cập nhật trạng thái đơn hàng
    public function updateStatus($orderId) {
        $newStatus = $_POST['order_status'] ?? '';
        $this->orderModel->updateOrderStatus($orderId, $newStatus);
        header("Location: " . APP_URL . "/AdminOrderController/detail/" . $orderId);
        exit();
    }
}
?>